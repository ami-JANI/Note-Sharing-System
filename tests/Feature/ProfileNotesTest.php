<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileNotesTest extends TestCase
{
    use RefreshDatabase;

    private function note(User $owner, array $attrs = []): Note
    {
        $semester = Semester::create(['name' => 'S1', 'order' => 0]);
        $subject = Subject::create(['semester_id' => $semester->id, 'code' => 'CSE101', 'name' => 'Prog']);

        return Note::create(array_merge([
            'subject_id' => $subject->id,
            'uploader_id' => $owner->id,
            'title' => 'My Note',
            'course_no' => 'CSE101',
            'course_title' => 'Prog',
            'file_path' => 'notes/x.pdf',
            'status' => 'approved',
        ], $attrs));
    }

    public function test_profile_shows_own_info_and_uploads_including_pending(): void
    {
        $user = User::factory()->create(['name' => 'Alice', 'department' => 'CSE']);
        $this->note($user, ['title' => 'Approved One', 'status' => 'approved']);
        $this->note($user, ['title' => 'Pending One', 'status' => 'pending']);

        $this->actingAs($user)->get(route('profile.show'))
            ->assertOk()
            ->assertSee('Alice')
            ->assertSee('CSE')
            ->assertSee('Approved One')
            ->assertSee('Pending One')
            ->assertSee('Pending approval');
    }

    public function test_owner_can_delete_their_note(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $note = $this->note($user);

        $this->actingAs($user)->delete(route('notes.destroy', $note))->assertRedirect();

        $this->assertDatabaseMissing('notes', ['id' => $note->id]);
    }

    public function test_non_owner_cannot_delete_a_note(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $note = $this->note($owner);

        $this->actingAs($other)->delete(route('notes.destroy', $note))->assertForbidden();
        $this->assertDatabaseHas('notes', ['id' => $note->id]);
    }

    public function test_owner_can_hide_and_unhide_a_note(): void
    {
        $user = User::factory()->create();
        $note = $this->note($user, ['hidden' => false]);

        $this->actingAs($user)->patch(route('notes.visibility', $note))->assertRedirect();
        $this->assertTrue($note->fresh()->hidden);

        $this->actingAs($user)->patch(route('notes.visibility', $note))->assertRedirect();
        $this->assertFalse($note->fresh()->hidden);
    }

    public function test_hidden_note_is_excluded_from_browse(): void
    {
        $user = User::factory()->create();
        $this->note($user, ['title' => 'Hidden Note', 'hidden' => true]);
        $this->note($user, ['title' => 'Visible Note', 'hidden' => false]);

        $this->actingAs($user)->get(route('browse.index'))
            ->assertSee('Visible Note')
            ->assertDontSee('Hidden Note');
    }

    public function test_non_owner_cannot_toggle_visibility(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $note = $this->note($owner);

        $this->actingAs($other)->patch(route('notes.visibility', $note))->assertForbidden();
    }
}
