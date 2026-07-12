<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\NotePurchase;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NoteController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'title' => ['required', 'string', 'max:255'],
            'course_no' => ['required', 'string', 'max:50'],
            'course_title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file' => ['required', 'file', 'mimes:pdf,docx', 'max:10240'],
            'credit_price' => ['nullable', 'integer', 'min:0'],
        ]);

        $path = $request->file('file')->store('notes', 'public');

        Note::create([
            'subject_id' => $validated['subject_id'],
            'uploader_id' => $request->user()->id,
            'title' => $validated['title'],
            'course_no' => $validated['course_no'],
            'course_title' => $validated['course_title'],
            'description' => $validated['description'] ?? null,
            'file_path' => $path,
            'credit_price' => $validated['credit_price'] ?? 0,
            'status' => 'pending',
        ]);

        return back()->with('status', 'Note uploaded and awaiting admin approval.');
    }

    public function show(Note $note)
    {
        abort_unless($note->status === 'approved', 404);

        $note->load('uploader', 'subject');

        return view('notes.show', compact('note'));
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

        return Storage::disk('public')->download($note->file_path, $note->title);
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

        return back()->with('status', 'Note unlocked.');
    }
}
