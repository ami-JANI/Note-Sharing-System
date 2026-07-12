<?php

namespace Tests\Feature\Admin;

use App\Models\Note;
use App\Models\Review;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminReviewModerationTest extends TestCase
{
    use RefreshDatabase;

    private Review $review;
    private User $admin;
    private User $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->student = User::factory()->create(['role' => 'student']);

        $semester = Semester::create(['name' => 'Semester 1', 'order' => 1]);
        $subject = Subject::create(['semester_id' => $semester->id, 'code' => 'CSE101', 'name' => 'Programming']);
        $note = Note::create([
            'subject_id' => $subject->id,
            'uploader_id' => User::factory()->create()->id,
            'title' => 'Test Note',
            'file_path' => 'notes/test.pdf',
            'status' => 'approved',
        ]);

        $this->review = Review::create([
            'note_id' => $note->id,
            'user_id' => $this->student->id,
            'rating' => 3,
            'comment' => 'Okay note',
        ]);
    }

    public function test_admin_can_hide_a_review(): void
    {
        $this->actingAs($this->admin)->post(route('admin.reviews.hide', $this->review))
            ->assertRedirect();

        $this->assertEquals(1, $this->review->fresh()->is_hidden);
    }

    public function test_hidden_review_stops_showing_on_note_page(): void
    {
        $this->review->update(['is_hidden' => true]);

        $this->assertEquals(0, $this->review->note->visibleReviews()->count());
        $this->assertEquals(0.0, $this->review->note->averageRating());
    }

    public function test_admin_can_delete_a_review(): void
    {
        $this->actingAs($this->admin)->delete(route('admin.reviews.destroy', $this->review))
            ->assertRedirect();

        $this->assertDatabaseMissing('reviews', ['id' => $this->review->id]);
    }

    public function test_non_admin_cannot_hide_review(): void
    {
        $this->actingAs($this->student)->post(route('admin.reviews.hide', $this->review))
            ->assertForbidden();
    }

    public function test_non_admin_cannot_delete_review(): void
    {
        $this->actingAs($this->student)->delete(route('admin.reviews.destroy', $this->review))
            ->assertForbidden();
    }
}
