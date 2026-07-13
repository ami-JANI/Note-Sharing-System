<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Toggle whether the authenticated user has favorited the given uploader.
     */
    public function toggle(Request $request, User $user)
    {
        $me = $request->user();

        if ($me->id === $user->id) {
            return back()->withErrors(['favorite' => 'You cannot favorite yourself.']);
        }

        if ($me->hasFavorited($user)) {
            $me->favoriteUploaders()->detach($user->id);
            $status = 'Removed from favorites.';
        } else {
            $me->favoriteUploaders()->attach($user->id);
            $status = 'Added to favorites.';
        }

        return back()->with('status', $status);
    }
}
