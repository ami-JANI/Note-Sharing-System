<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * All uploads regardless of status, optionally filtered by status.
     */
    public function index(Request $request)
    {
        $query = Note::with('uploader')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $notes = $query->paginate(20)->withQueryString();

        return view('admin.notes', compact('notes'));
    }

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
