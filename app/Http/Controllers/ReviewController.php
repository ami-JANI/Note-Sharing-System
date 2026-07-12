<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Note $note)
    {
        abort_unless($note->isUnlockedBy($request->user()), 403, 'You must unlock this note before reviewing.');

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        if (Review::where('note_id', $note->id)->where('user_id', $request->user()->id)->exists()) {
            return back()->withErrors(['review' => 'You have already reviewed this note.']);
        }

        Review::create([
            'note_id' => $note->id,
            'user_id' => $request->user()->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return back()->with('status', 'Review submitted.');
    }
}
