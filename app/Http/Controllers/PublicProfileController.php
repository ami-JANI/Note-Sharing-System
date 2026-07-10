<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PublicProfileController extends Controller
{
    public function show(User $user)
    {
        $notes = $user->notes()->latest()->get();
        $averageRating = 0;

        return view('users.show', compact('user', 'notes', 'averageRating'));
    }
}
