<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function hide(Review $review)
    {
        $review->update(['is_hidden' => true]);

        return back()->with('status', 'Review hidden.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('status', 'Review deleted.');
    }
}
