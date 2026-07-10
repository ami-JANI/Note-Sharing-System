<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Note;

class NoteController extends Controller
{
    public function pending()
    {
        $pendingNotes = Note::where('status', 'pending')
            ->with(['subject', 'uploader'])
            ->latest()
            ->get();

        return view('admin.pending-notes', compact('pendingNotes'));
    }

    public function approve(Note $note)
    {
        $note->update(['status' => 'approved']);

        return back()->with('status', 'Note approved.');
    }

    public function reject(Note $note)
    {
        $note->update(['status' => 'rejected']);

        return back()->with('status', 'Note rejected.');
    }
}
