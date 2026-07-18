<?php

namespace App\Notifications;

use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Message $message,
        public User $sender,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $preview = mb_substr($this->message->body, 0, 100);

        return [
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name,
            'preview' => $preview,
            'message' => "{$this->sender->name}: {$preview}",
            'url' => route('messages.show', $this->sender),
        ];
    }
}
