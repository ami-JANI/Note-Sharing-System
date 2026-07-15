<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_id' => ['required', 'exists:users,id'],
            'body' => ['required', 'string', 'max:2000'],
        ]);

        if ((int) $validated['recipient_id'] === $request->user()->id) {
            return back()->withErrors(['recipient_id' => 'You cannot send a message to yourself.']);
        }

        $message = Message::create([
            'sender_id' => $request->user()->id,
            'recipient_id' => $validated['recipient_id'],
            'body' => $validated['body'],
        ]);

        $recipient = User::find($validated['recipient_id']);
        $recipient->notify(new NewMessageNotification($message, $request->user()));

        return back()->with('status', 'Message sent.');
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $messages = Message::where('sender_id', $user->id)
            ->orWhere('recipient_id', $user->id)
            ->with('sender', 'recipient')
            ->latest()
            ->get()
            ->groupBy(fn($m) => $m->sender_id === $user->id ? $m->recipient_id : $m->sender_id)
            ->map(function ($group) use ($user) {
                $latest = $group->sortByDesc('created_at')->first();
                $partner = $latest->sender_id === $user->id ? $latest->recipient : $latest->sender;
                $unread = $group->where('recipient_id', $user->id)->whereNull('read_at')->count();

                return (object) [
                    'partner' => $partner,
                    'latest_message' => $latest,
                    'unread_count' => $unread,
                ];
            })
            ->sortByDesc(fn($c) => $c->latest_message->created_at)
            ->values();

        return view('messages.index', compact('messages'));
    }

    public function show(Request $request, User $user)
    {
        $authUser = $request->user();

        if ($user->id === $authUser->id) {
            return back()->withErrors(['user' => 'Cannot open a conversation with yourself.']);
        }

        $messages = Message::where(function ($q) use ($authUser, $user) {
            $q->where('sender_id', $authUser->id)->where('recipient_id', $user->id);
        })->orWhere(function ($q) use ($authUser, $user) {
            $q->where('sender_id', $user->id)->where('recipient_id', $authUser->id);
        })->orderBy('created_at')->get();

        Message::where('sender_id', $user->id)
            ->where('recipient_id', $authUser->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('messages.show', compact('messages', 'user'));
    }
}
