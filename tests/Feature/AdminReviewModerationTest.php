<?php

namespace Tests\Feature;

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

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $semester = Semester::create(['name' => 'Semester 1', 'order' => 0]);
        $subject = Subject::create(['semester_id' => $semester->id, 'code' => 'CSE101', 'name' => 'Programming']);
        $uploader = User::factory()->create();
        $note = Note::create([
            'subject_id' => $subject->id,
            'uploader_id' => $uploader->id,
            'title' => 'Test Note',
            'file_path' => 'notes/test.pdf',
            'status' => 'approved',
        ]);
        $reviewer = User::factory()->create();

        $this->review = Review::create([
            'note_id' => $note->id,
            'user_id' => $reviewer->id,
            'rating' => 5,
            'comment' => 'Great notes!',
        ]);
    }

    public function test_non_admin_cannot_access_reviews_page(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($student)->get(route('admin.reviews'))->assertForbidden();
    }

    public function test_admin_can_view_reviews_page(): void
    {
        $this->actingAs($this->admin)->get(route('admin.reviews'))->assertOk();
    }

    public function test_admin_can_hide_a_review(): void
    {
        $this->actingAs($this->admin)->post(route('admin.reviews.hide', $this->review))->assertRedirect();

        $this->assertTrue($this->review->fresh()->is_hidden);
    }

    public function test_admin_can_delete_a_review(): void
    {
        $this->actingAs($this->admin)->post(route('admin.reviews.delete', $this->review))->assertRedirect();

        $this->assertDatabaseMissing('reviews', ['id' => $this->review->id]);
    }

    public function test_reviews_page_shows_reviews(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reviews'));

        $response->assertSee('Great notes!');
    }
}
