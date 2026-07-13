<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileSocialSectionsTest extends TestCase
{
    use RefreshDatabase;

    private function note(User $owner, array $attrs = []): Note
    {
        return Note::create(array_merge([
            'uploader_id' => $owner->id,
            'title' => 'Downloadable Note',
            'course_no' => 'CSE101',
            'course_title' => 'Prog',
            'file_path' => 'notes/x.pdf',
            'credit_price' => 0,
            'status' => 'approved',
        ], $attrs));
    }

    public function test_downloading_a_note_records_it(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('notes/x.pdf', 'dummy');

        $me = User::factory()->create();
        $uploader = User::factory()->create();
        $note = $this->note($uploader);

        $this->actingAs($me)->get(route('notes.download', $note))->assertOk();

        $this->assertDatabaseHas('note_downloads', [
            'user_id' => $me->id,
            'note_id' => $note->id,
        ]);
    }

    public function test_redownloading_does_not_duplicate_the_record(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('notes/x.pdf', 'dummy');

        $me = User::factory()->create();
        $note = $this->note(User::factory()->create());

        $this->actingAs($me)->get(route('notes.download', $note));
        $this->actingAs($me)->get(route('notes.download', $note));

        $this->assertDatabaseCount('note_downloads', 1);
    }

    public function test_profile_shows_downloads_favorites_and_reviews(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('notes/x.pdf', 'dummy');

        $me = User::factory()->create();
        $uploader = User::factory()->create(['name' => 'Prof Uploader']);
        $note = $this->note($uploader, ['title' => 'Amazing Lecture Notes']);

        // Download, favorite the uploader, and leave a review.
        $this->actingAs($me)->get(route('notes.download', $note));
        $me->favoriteUploaders()->attach($uploader->id);
        Review::create([
            'note_id' => $note->id,
            'user_id' => $me->id,
            'rating' => 5,
            'comment' => 'Really helpful set of notes',
        ]);

        $this->actingAs($me)->get(route('profile.show'))
            ->assertOk()
            ->assertSee('Downloaded Notes')
            ->assertSee('Amazing Lecture Notes')
            ->assertSee('Favorite Uploaders')
            ->assertSee('Prof Uploader')
            ->assertSee("Reviews You've Given", false)
            ->assertSee('Really helpful set of notes');
    }

    public function test_empty_states_render(): void
    {
        $me = User::factory()->create();

        $this->actingAs($me)->get(route('profile.show'))
            ->assertOk()
            ->assertSee("You haven't downloaded any notes yet.", false)
            ->assertSee("You haven't favorited any uploaders yet.", false)
            ->assertSee("You haven't reviewed any notes yet.", false);
    }
}
