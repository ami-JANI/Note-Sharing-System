<?php

namespace App\Http\Controllers;

use App\Models\Note;
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
            'description' => ['nullable', 'string'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,ppt,pptx', 'max:10240'],
        ]);

        $path = $request->file('file')->store('notes', 'public');

        Note::create([
            'subject_id' => $validated['subject_id'],
            'uploader_id' => $request->user()->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'file_path' => $path,
        ]);

        return back()->with('status', 'Note uploaded.');
    }

    public function download(Note $note)
    {
        return Storage::disk('public')->download($note->file_path, $note->title);
    }
}
