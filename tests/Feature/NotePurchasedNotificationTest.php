<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use App\Notifications\NotePurchasedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NotePurchasedNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_fired_when_note_is_purchased(): void
    {
        Notification::fake();

        $uploader = User::factory()->create(['credits' => 0]);
        $buyer = User::factory()->create(['credits' => 100]);
        $semester = Semester::create(['name' => 'Semester 1', 'order' => 1]);
        $subject = Subject::create(['semester_id' => $semester->id, 'code' => 'CS101', 'name' => 'CS']);
        Storage::fake('public');
        $file = UploadedFile::fake()->create('notes.pdf', 100);

        $this->actingAs($uploader)->post(route('notes.store'), [
            'subject_id' => $subject->id,
            'title' => 'Paid Note',
            'course_no' => 'CS 101',
            'course_title' => 'Programming',
            'file' => $file,
            'credit_price' => 10,
        ]);

        $note = Note::first();
        $note->update(['status' => 'approved']);

        $this->actingAs($buyer)->post(route('notes.unlock', $note));

        Notification::assertSentTo(
            $uploader,
            NotePurchasedNotification::class,
            fn($n) => $n->note->id === $note->id && $n->buyer->id === $buyer->id
        );
    }

    public function test_notification_lands_in_database(): void
    {
        $uploader = User::factory()->create(['credits' => 0]);
        $buyer = User::factory()->create(['credits' => 100]);
        $semester = Semester::create(['name' => 'Semester 1', 'order' => 1]);
        $subject = Subject::create(['semester_id' => $semester->id, 'code' => 'CS101', 'name' => 'CS']);
        Storage::fake('public');
        $file = UploadedFile::fake()->create('notes.pdf', 100);

        $this->actingAs($uploader)->post(route('notes.store'), [
            'subject_id' => $subject->id,
            'title' => 'Paid Note DB',
            'course_no' => 'CS 101',
            'course_title' => 'Programming',
            'file' => $file,
            'credit_price' => 10,
        ]);

        $note = Note::first();
        $note->update(['status' => 'approved']);

        $this->actingAs($buyer)->post(route('notes.unlock', $note));

        $this->assertDatabaseHas('notifications', [
            'type' => NotePurchasedNotification::class,
            'notifiable_id' => $uploader->id,
        ]);
    }
}
