<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();

        return view('admin.users', compact('users'));
    }

    public function suspend(User $user)
    {
        abort_if($user->isAdmin(), 403, 'Admin accounts cannot be suspended.');

        $user->update(['status' => 'suspended']);

        return back()->with('status', "{$user->name} suspended.");
    }

    public function unsuspend(User $user)
    {
        $user->update(['status' => 'active']);

        return back()->with('status', "{$user->name} reinstated.");
    }
}
