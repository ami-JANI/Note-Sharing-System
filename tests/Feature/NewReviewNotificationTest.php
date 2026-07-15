<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use App\Notifications\NewReviewNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NewReviewNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_fired_when_review_is_submitted(): void
    {
        Notification::fake();

        $uploader = User::factory()->create();
        $reviewer = User::factory()->create();
        $semester = Semester::create(['name' => 'Semester 1', 'order' => 1]);
        $subject = Subject::create(['semester_id' => $semester->id, 'code' => 'CS101', 'name' => 'CS']);
        Storage::fake('public');
        $file = UploadedFile::fake()->create('notes.pdf', 100);

        $this->actingAs($uploader)->post(route('notes.store'), [
            'subject_id' => $subject->id,
            'title' => 'Review Note',
            'course_no' => 'CS 101',
            'course_title' => 'Programming',
            'file' => $file,
        ]);

        $note = Note::first();
        $note->update(['status' => 'approved', 'credit_price' => 0]);

        $this->actingAs($reviewer)->post(route('reviews.store', $note), [
            'rating' => 4,
            'comment' => 'Good notes!',
        ]);

        Notification::assertSentTo(
            $uploader,
            NewReviewNotification::class,
            fn($n) => $n->note->id === $note->id && $n->reviewer->id === $reviewer->id
        );
    }

    public function test_notification_lands_in_database(): void
    {
        $uploader = User::factory()->create();
        $reviewer = User::factory()->create();
        $semester = Semester::create(['name' => 'Semester 1', 'order' => 1]);
        $subject = Subject::create(['semester_id' => $semester->id, 'code' => 'CS101', 'name' => 'CS']);
        Storage::fake('public');
        $file = UploadedFile::fake()->create('notes.pdf', 100);

        $this->actingAs($uploader)->post(route('notes.store'), [
            'subject_id' => $subject->id,
            'title' => 'Review Note DB',
            'course_no' => 'CS 101',
            'course_title' => 'Programming',
            'file' => $file,
        ]);

        $note = Note::first();
        $note->update(['status' => 'approved', 'credit_price' => 0]);

        $this->actingAs($reviewer)->post(route('reviews.store', $note), [
            'rating' => 5,
            'comment' => 'Excellent!',
        ]);

        $this->assertDatabaseHas('notifications', [
            'type' => NewReviewNotification::class,
            'notifiable_id' => $uploader->id,
        ]);
    }
}
