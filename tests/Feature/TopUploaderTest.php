<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TopUploaderTest extends TestCase
{
    use RefreshDatabase;

    public function test_top_uploader_by_weekly_avg_rating(): void
    {
        $uploaderA = User::factory()->create(['name' => 'Uploader A']);
        $uploaderB = User::factory()->create(['name' => 'Uploader B']);

        $noteA = Note::create(['uploader_id' => $uploaderA->id, 'title' => 'Note A', 'course_no' => 'CSE101', 'course_title' => 'CS', 'file_path' => 'a.pdf', 'status' => 'approved', 'hidden' => false]);
        $noteB = Note::create(['uploader_id' => $uploaderB->id, 'title' => 'Note B', 'course_no' => 'CSE101', 'course_title' => 'CS', 'file_path' => 'b.pdf', 'status' => 'approved', 'hidden' => false]);

        Review::create(['note_id' => $noteA->id, 'user_id' => User::factory()->create()->id, 'rating' => 5, 'comment' => 'Great', 'created_at' => now()]);
        Review::create(['note_id' => $noteA->id, 'user_id' => User::factory()->create()->id, 'rating' => 4, 'comment' => 'Good', 'created_at' => now()]);

        Review::create(['note_id' => $noteB->id, 'user_id' => User::factory()->create()->id, 'rating' => 3, 'comment' => 'Okay', 'created_at' => now()]);

        $response = $this->actingAs(User::factory()->create())->get(route('browse.index'));

        $response->assertOk();
        $response->assertViewHas('topUploader', fn($u) => $u && $u->id === $uploaderA->id && $u->avg_rating == 4.5);
    }

    public function test_top_uploader_falls_back_to_all_time(): void
    {
        $uploaderA = User::factory()->create(['name' => 'Uploader A']);
        $uploaderB = User::factory()->create(['name' => 'Uploader B']);

        $noteA = Note::create(['uploader_id' => $uploaderA->id, 'title' => 'Note A', 'course_no' => 'CSE101', 'course_title' => 'CS', 'file_path' => 'a.pdf', 'status' => 'approved', 'hidden' => false]);
        $noteB = Note::create(['uploader_id' => $uploaderB->id, 'title' => 'Note B', 'course_no' => 'CSE101', 'course_title' => 'CS', 'file_path' => 'b.pdf', 'status' => 'approved', 'hidden' => false]);

        Review::create(['note_id' => $noteA->id, 'user_id' => User::factory()->create()->id, 'rating' => 3, 'comment' => 'Okay', 'created_at' => now()->subDays(10)]);
        Review::create(['note_id' => $noteB->id, 'user_id' => User::factory()->create()->id, 'rating' => 5, 'comment' => 'Great', 'created_at' => now()->subDays(10)]);

        $response = $this->actingAs(User::factory()->create())->get(route('browse.index'));

        $response->assertOk();
        $response->assertViewHas('topUploader', fn($u) => $u && $u->id === $uploaderB->id && $u->avg_rating == 5.0);
    }
}
