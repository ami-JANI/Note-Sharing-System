<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminNoteApprovalTest extends TestCase
{
    use RefreshDatabase;

    protected function makeNote(string $status = 'pending'): Note
    {
        $semester = Semester::create(['name' => 'Semester 1', 'order' => 0]);
        $subject = Subject::create(['semester_id' => $semester->id, 'code' => 'CSE101', 'name' => 'Programming']);
        $uploader = User::factory()->create();

        return Note::create([
            'subject_id' => $subject->id,
            'uploader_id' => $uploader->id,
            'title' => 'Test Note',
            'file_path' => 'notes/test.pdf',
            'status' => $status,
        ]);
    }

    public function test_new_notes_are_pending_and_hidden_from_subject_page(): void
    {
        $note = $this->makeNote('pending');
        $student = User::factory()->create();

        $response = $this->actingAs($student)->get(route('subjects.show', $note->subject));

        $response->assertDontSee($note->title);
    }

    public function test_approved_notes_are_visible_on_subject_page(): void
    {
        $note = $this->makeNote('approved');
        $student = User::factory()->create();

        $response = $this->actingAs($student)->get(route('subjects.show', $note->subject));

        $response->assertSee($note->title);
    }

    public function test_non_admin_cannot_access_pending_queue(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($student)->get(route('admin.notes.pending'))->assertForbidden();
    }

    public function test_admin_can_view_pending_queue(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->makeNote('pending');

        $this->actingAs($admin)->get(route('admin.notes.pending'))->assertOk();
    }

    public function test_admin_can_approve_a_note(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $note = $this->makeNote('pending');

        $this->actingAs($admin)->post(route('admin.notes.approve', $note))->assertRedirect();

        $this->assertEquals('approved', $note->fresh()->status);
    }

    public function test_admin_can_reject_a_note(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $note = $this->makeNote('pending');

        $this->actingAs($admin)->post(route('admin.notes.reject', $note))->assertRedirect();

        $this->assertEquals('rejected', $note->fresh()->status);
    }
}
