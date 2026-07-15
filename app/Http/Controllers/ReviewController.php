<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Review;
use App\Notifications\NewReviewNotification;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Note $note)
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        if (! $note->isUnlockedBy($request->user())) {
            return back()->withErrors(['purchase' => 'You must unlock this note before reviewing it.']);
        }

        $review = $note->reviews()->create([
            'user_id' => $request->user()->id,
            'rating' => $data['rating'],
            'comment' => $data['comment'],
        ]);

        $note->uploader->notify(new NewReviewNotification($note, $review, $request->user()));

        return back()->with('status', 'Review submitted successfully.');
    }
}
