<?php

namespace App\Notifications;

use App\Models\Note;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NotePurchasedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Note $note,
        public User $buyer,
        public int $creditsEarned,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'note_id' => $this->note->id,
            'note_title' => $this->note->title,
            'buyer_name' => $this->buyer->name,
            'credits_earned' => $this->creditsEarned,
            'message' => "{$this->buyer->name} unlocked \"{$this->note->title}\" — you earned {$this->creditsEarned} credits",
            'url' => route('notes.show', $this->note),
        ];
    }
}
