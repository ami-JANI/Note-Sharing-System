<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NoteReviewTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $other;
    private Note $note;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->other = User::factory()->create();

        $semester = Semester::create(['name' => 'Semester 1', 'order' => 1]);
        $subject = Subject::create([
            'semester_id' => $semester->id,
            'code' => 'CS101',
            'name' => 'Computer Science',
        ]);

        Storage::fake('public');
        $file = UploadedFile::fake()->create('notes.pdf', 100);

        $this->actingAs($this->user)->post(route('notes.store'), [
            'subject_id' => $subject->id,
            'title' => 'Review Test Note',
            'course_no' => 'CS 101',
            'course_title' => 'Programming',
            'file' => $file,
        ]);

        $this->note = Note::first();
        $this->note->update(['status' => 'approved', 'credit_price' => 0]);
    }

    public function test_user_can_review_an_unlocked_note(): void
    {
        $response = $this->actingAs($this->other)->post(route('reviews.store', $this->note), [
            'rating' => 5,
            'comment' => 'Excellent notes!',
        ]);

        $response->assertSessionHas('status', 'Review submitted successfully.');
        $this->assertDatabaseHas('reviews', [
            'note_id' => $this->note->id,
            'user_id' => $this->other->id,
            'rating' => 5,
            'comment' => 'Excellent notes!',
        ]);
    }

    public function test_unauthenticated_user_cannot_review(): void
    {
        $response = $this->post(route('reviews.store', $this->note), [
            'rating' => 4,
            'comment' => 'Good notes.',
        ]);

        $response->assertRedirect();
    }

    public function test_rating_is_required_and_must_be_between_1_and_5(): void
    {
        $response = $this->actingAs($this->other)->post(route('reviews.store', $this->note), [
            'comment' => 'Missing rating.',
        ]);

        $response->assertSessionHasErrors('rating');

        $response = $this->actingAs($this->other)->post(route('reviews.store', $this->note), [
            'rating' => 0,
            'comment' => 'Invalid rating.',
        ]);

        $response->assertSessionHasErrors('rating');

        $response = $this->actingAs($this->other)->post(route('reviews.store', $this->note), [
            'rating' => 6,
            'comment' => 'Invalid rating.',
        ]);

        $response->assertSessionHasErrors('rating');
    }

    public function test_comment_is_required(): void
    {
        $response = $this->actingAs($this->other)->post(route('reviews.store', $this->note), [
            'rating' => 3,
        ]);

        $response->assertSessionHasErrors('comment');
    }
}
