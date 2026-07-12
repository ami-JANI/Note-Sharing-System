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

        $s1 = Semester::create(['name' => 'Semester 1', 'order' => 1]);
        $sub1 = Subject::create(['semester_id' => $s1->id, 'code' => 'CSE101', 'name' => 'Programming']);
        $sub2 = Subject::create(['semester_id' => $s1->id, 'code' => 'CSE201', 'name' => 'Data Structures']);
        $u = User::factory()->create();

        Note::create(['subject_id' => $sub1->id, 'uploader_id' => $u->id, 'title' => 'PHP Basics', 'course_no' => 'CSE101', 'course_title' => 'Programming', 'file_path' => 'notes/a.pdf', 'status' => 'approved']);
        Note::create(['subject_id' => $sub2->id, 'uploader_id' => $u->id, 'title' => 'Linked Lists', 'course_no' => 'CSE201', 'course_title' => 'Data Structures', 'file_path' => 'notes/b.pdf', 'status' => 'approved']);
        Note::create(['subject_id' => $sub1->id, 'uploader_id' => $u->id, 'title' => 'Hidden', 'course_no' => 'CSE101', 'course_title' => 'Programming', 'file_path' => 'notes/c.pdf', 'status' => 'pending']);
    }

    public function test_shows_all_approved_notes(): void
    {
        $r = $this->actingAs(User::factory()->create())->get(route('browse.index'));
        $r->assertSee('PHP Basics')->assertSee('Linked Lists')->assertDontSee('Hidden');
    }

    public function test_filters_by_title(): void
    {
        $r = $this->actingAs(User::factory()->create())->get(route('browse.index', ['q' => 'PHP']));
        $r->assertSee('PHP Basics')->assertDontSee('Linked Lists');
    }

    public function test_filters_by_course_no(): void
    {
        $r = $this->actingAs(User::factory()->create())->get(route('browse.index', ['q' => 'CSE201']));
        $r->assertSee('Linked Lists')->assertDontSee('PHP Basics');
    }

    public function test_filters_by_course_title(): void
    {
        $r = $this->actingAs(User::factory()->create())->get(route('browse.index', ['q' => 'Structures']));
        $r->assertSee('Linked Lists')->assertDontSee('PHP Basics');
    }

    public function test_empty_when_no_match(): void
    {
        $r = $this->actingAs(User::factory()->create())->get(route('browse.index', ['q' => 'ZZZZ']));
        $r->assertSee("No results for");
    }
}
