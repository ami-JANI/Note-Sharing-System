<?php

namespace App\Notifications;

use App\Models\Note;
use App\Models\Review;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewReviewNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Note $note,
        public Review $review,
        public User $reviewer,
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
            'rating' => $this->review->rating,
            'reviewer_name' => $this->reviewer->name,
            'message' => "{$this->reviewer->name} left a {$this->review->rating}-star review on \"{$this->note->title}\"",
            'url' => route('notes.show', $this->note),
        ];
    }
}
