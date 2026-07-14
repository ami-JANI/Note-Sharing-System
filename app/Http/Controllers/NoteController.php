<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\NotePurchase;
use App\Models\Subject;
use App\Notifications\NotePurchasedNotification;
use App\Services\NotePreviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NoteController extends Controller
{
    public function create()
    {
        return view('notes.create', [
            'semesters' => \App\Models\Semester::orderBy('order')->get(),
            'departments' => config('note-sharing.departments'),
        ]);
    }

    public function store(Request $request, NotePreviewService $previews)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'course_no' => ['required', 'string', 'max:50'],
            'course_title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file' => ['required', 'file', 'mimes:pdf,docx', 'max:10240'],
            'credit_price' => ['nullable', 'integer', 'min:0'],
            'department' => ['nullable', 'string', 'max:255'],
            'semester_id' => ['nullable', 'exists:semesters,id'],
        ]);

        $path = $request->file('file')->store('notes', 'public');

        $note = Note::create([
            'uploader_id' => $request->user()->id,
            'title' => $validated['title'],
            'course_no' => $validated['course_no'],
            'course_title' => $validated['course_title'],
            'description' => $validated['description'] ?? null,
            'file_path' => $path,
            'credit_price' => $validated['credit_price'] ?? 0,
            'department' => $validated['department'] ?? null,
            'semester_id' => $validated['semester_id'] ?? null,
            'status' => 'pending',
        ]);

        // Best-effort preview generation (no-op if the file isn't a PDF or
        // Ghostscript isn't installed).
        $previews->generate($note);

        return back()->with('status', 'Note uploaded and awaiting admin approval.');
    }

    public function show(Note $note)
    {
        abort_unless($note->status === 'approved', 404);

        $note->load('uploader', 'subject');

        return view('notes.show', compact('note'));
    }

    public function destroy(Note $note)
    {
        abort_unless($note->uploader_id === auth()->id(), 403);

        Storage::disk('public')->delete($note->file_path);

        $note->delete();

        return back()->with('status', 'Note deleted.');
    }

    public function toggleVisibility(Note $note)
    {
        abort_unless($note->uploader_id === auth()->id(), 403);

        $note->update(['hidden' => ! $note->hidden]);

        return back()->with('status', $note->hidden ? 'Note hidden.' : 'Note is visible again.');
    }

    public function download(Note $note)
    {
        $user = auth()->user();

        if ($note->credit_price > 0
            && $note->uploader_id !== $user->id
            && ! NotePurchase::where('note_id', $note->id)->where('user_id', $user->id)->exists()
        ) {
            return back()->withErrors(['note' => 'You must unlock this note first.']);
        }

        // Record the download so it can surface on the user's profile.
        // updateOrCreate keeps one row per user/note and refreshes the timestamp.
        $note->downloads()->updateOrCreate(['user_id' => $user->id], []);

        $extension = pathinfo($note->file_path, PATHINFO_EXTENSION);
        $downloadName = $note->title.($extension ? '.'.$extension : '');

        return Storage::disk('public')->download($note->file_path, $downloadName);
    }

    public function unlock(Request $request, Note $note)
    {
        $user = $request->user();

        if ($note->credit_price <= 0) {
            return back()->withErrors(['note' => 'This note is already free.']);
        }

        if ($note->uploader_id === $user->id) {
            return back()->withErrors(['note' => 'You cannot unlock your own note.']);
        }

        if (NotePurchase::where('note_id', $note->id)->where('user_id', $user->id)->exists()) {
            return back()->withErrors(['note' => 'You have already unlocked this note.']);
        }

        if ($user->credits < $note->credit_price) {
            return back()->withErrors(['credits' => 'Insufficient credits. Please purchase more.']);
        }

        $user->decrement('credits', $note->credit_price);

        $note->uploader->increment('credits', (int) round($note->credit_price * 0.1));

        NotePurchase::create([
            'note_id' => $note->id,
            'user_id' => $user->id,
            'credits_spent' => $note->credit_price,
        ]);

        $note->uploader->notify(new NotePurchasedNotification($note, $user, (int) round($note->credit_price * 0.1)));

        return back()->with('status', 'Note unlocked.');
    }
}
