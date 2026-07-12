<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\NotePurchase;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    private Note $note;
    private User $uploader;
    private User $buyer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uploader = User::factory()->create();
        $this->buyer = User::factory()->create();

        $semester = Semester::create(['name' => 'Semester 1', 'order' => 1]);
        $subject = Subject::create(['semester_id' => $semester->id, 'code' => 'CSE101', 'name' => 'Programming']);

        $this->note = Note::create([
            'subject_id' => $subject->id,
            'uploader_id' => $this->uploader->id,
            'title' => 'Test Note',
            'course_no' => 'CSE101',
            'course_title' => 'Programming',
            'file_path' => 'notes/test.pdf',
            'credit_price' => 5,
            'status' => 'approved',
        ]);

        NotePurchase::create([
            'note_id' => $this->note->id,
            'user_id' => $this->buyer->id,
            'credits_spent' => 5,
        ]);
    }

    public function test_buyer_can_review_purchased_note(): void
    {
        $response = $this->actingAs($this->buyer)->post(route('reviews.store', $this->note), [
            'rating' => 4,
            'comment' => 'Great note!',
        ]);

        $response->assertSessionHas('status', 'Review submitted.');
        $this->assertDatabaseHas('reviews', [
            'note_id' => $this->note->id,
            'user_id' => $this->buyer->id,
            'rating' => 4,
            'comment' => 'Great note!',
        ]);
    }

    public function test_non_buyer_gets_403_when_reviewing(): void
    {
        $stranger = User::factory()->create();

        $response = $this->actingAs($stranger)->post(route('reviews.store', $this->note), [
            'rating' => 4,
        ]);

        $response->assertForbidden();
    }

    public function test_user_cannot_review_same_note_twice(): void
    {
        $this->actingAs($this->buyer)->post(route('reviews.store', $this->note), [
            'rating' => 4,
        ]);

        $response = $this->actingAs($this->buyer)->post(route('reviews.store', $this->note), [
            'rating' => 5,
        ]);

        $response->assertSessionHasErrors('review');
    }

    public function test_rating_must_be_between_1_and_5(): void
    {
        $response = $this->actingAs($this->buyer)->post(route('reviews.store', $this->note), [
            'rating' => 6,
        ]);

        $response->assertSessionHasErrors('rating');

        $response = $this->actingAs($this->buyer)->post(route('reviews.store', $this->note), [
            'rating' => 0,
        ]);

        $response->assertSessionHasErrors('rating');
    }

    public function test_unauthenticated_user_cannot_review(): void
    {
        $response = $this->post(route('reviews.store', $this->note), [
            'rating' => 4,
        ]);

        $response->assertRedirect(route('login'));
    }
}
