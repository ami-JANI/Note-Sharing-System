<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AdminBroadcastNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public function create()
    {
        return view('admin.broadcast', ['users' => User::orderBy('name')->get()]);
    }

    public function broadcast(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:500'],
            'target' => ['required', 'in:all,specific'],
            'user_ids' => ['required_if:target,specific', 'array'],
            'user_ids.*' => ['exists:users,id'],
        ]);

        $users = $validated['target'] === 'all'
            ? User::all()
            : User::whereIn('id', $validated['user_ids'])->get();

        Notification::send($users, new AdminBroadcastNotification($validated['message']));

        return back()->with('status', 'Broadcast sent to '.$users->count().' user(s).');
    }
}
