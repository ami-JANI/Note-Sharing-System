<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminNoteApprovalTest extends TestCase
{
    use RefreshDatabase;

    protected function makeNote(string $status = 'pending', string $title = 'Test Note'): Note
    {
        $semester = Semester::create(['name' => 'Semester 1', 'order' => 0]);
        $subject = Subject::create(['semester_id' => $semester->id, 'code' => 'CSE101', 'name' => 'Programming']);
        $uploader = User::factory()->create();

        return Note::create([
            'subject_id' => $subject->id,
            'uploader_id' => $uploader->id,
            'title' => $title,
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

    public function test_non_admin_cannot_access_all_uploads(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($student)->get(route('admin.notes.index'))->assertForbidden();
    }

    public function test_admin_sees_all_uploads_regardless_of_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pending = $this->makeNote('pending', 'Widget Alpha');
        $approved = $this->makeNote('approved', 'Widget Beta');
        $rejected = $this->makeNote('rejected', 'Widget Gamma');

        $response = $this->actingAs($admin)->get(route('admin.notes.index'));

        $response->assertOk()
            ->assertSee($pending->title)
            ->assertSee($approved->title)
            ->assertSee($rejected->title);
    }

    public function test_admin_can_filter_all_uploads_by_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pending = $this->makeNote('pending', 'Widget Alpha');
        $approved = $this->makeNote('approved', 'Widget Beta');

        $response = $this->actingAs($admin)->get(route('admin.notes.index', ['status' => 'approved']));

        $response->assertOk()->assertSee($approved->title)->assertDontSee($pending->title);
    }

    public function test_admin_can_delete_any_note_regardless_of_owner_or_status(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $note = $this->makeNote('approved');

        $this->actingAs($admin)->delete(route('admin.notes.destroy', $note))->assertRedirect();

        $this->assertDatabaseMissing('notes', ['id' => $note->id]);
        Storage::disk('public')->assertMissing($note->file_path);
    }

    public function test_non_admin_cannot_delete_a_note(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $note = $this->makeNote('approved');

        $this->actingAs($student)->delete(route('admin.notes.destroy', $note))->assertForbidden();

        $this->assertDatabaseHas('notes', ['id' => $note->id]);
    }
}
