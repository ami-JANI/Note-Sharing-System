<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrowseSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $semester = Semester::create(['name' => 'Semester 1', 'order' => 1]);
        $subject1 = Subject::create(['semester_id' => $semester->id, 'code' => 'CSE101', 'name' => 'Programming']);
        $subject2 = Subject::create(['semester_id' => $semester->id, 'code' => 'CSE201', 'name' => 'Data Structures']);
        $uploader = User::factory()->create();

        Note::create([
            'subject_id' => $subject1->id,
            'uploader_id' => $uploader->id,
            'title' => 'PHP Basics',
            'course_no' => 'CSE101',
            'course_title' => 'Programming',
            'file_path' => 'notes/test1.pdf',
            'status' => 'approved',
        ]);

        Note::create([
            'subject_id' => $subject2->id,
            'uploader_id' => $uploader->id,
            'title' => 'Linked Lists',
            'course_no' => 'CSE201',
            'course_title' => 'Data Structures',
            'file_path' => 'notes/test2.pdf',
            'status' => 'approved',
        ]);

        Note::create([
            'subject_id' => $subject1->id,
            'uploader_id' => $uploader->id,
            'title' => 'Hidden Draft',
            'course_no' => 'CSE101',
            'course_title' => 'Programming',
            'file_path' => 'notes/test3.pdf',
            'status' => 'pending',
        ]);
    }

    public function test_browse_shows_all_approved_notes(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('browse.index'));

        $response->assertOk();
        $response->assertSee('PHP Basics');
        $response->assertSee('Linked Lists');
        $response->assertDontSee('Hidden Draft');
    }

    public function test_browse_filters_by_title(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('browse.index', ['q' => 'PHP']));

        $response->assertOk();
        $response->assertSee('PHP Basics');
        $response->assertDontSee('Linked Lists');
    }

    public function test_browse_filters_by_course_no(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('browse.index', ['q' => 'CSE201']));

        $response->assertOk();
        $response->assertSee('Linked Lists');
        $response->assertDontSee('PHP Basics');
    }

    public function test_browse_filters_by_course_title(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('browse.index', ['q' => 'Structures']));

        $response->assertOk();
        $response->assertSee('Linked Lists');
        $response->assertDontSee('PHP Basics');
    }

    public function test_browse_returns_empty_when_no_match(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('browse.index', ['q' => 'ZZZZNOTEXIST']));

        $response->assertOk();
        $response->assertSee('No notes found');
    }
}
