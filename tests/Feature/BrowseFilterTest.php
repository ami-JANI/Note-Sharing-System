<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\Review;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrowseFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $s1 = Semester::create(['name' => 'Semester 1', 'order' => 1]);
        $s2 = Semester::create(['name' => 'Semester 2', 'order' => 2]);
        $u = User::factory()->create();
        $rater = User::factory()->create();

        $free = Note::create(['uploader_id' => $u->id, 'title' => 'Free CSE Note', 'course_no' => 'CSE101', 'course_title' => 'Programming', 'department' => 'CSE', 'semester_id' => $s1->id, 'file_path' => 'notes/a.pdf', 'status' => 'approved', 'credit_price' => 0]);
        $paid = Note::create(['uploader_id' => $u->id, 'title' => 'Paid EEE Note', 'course_no' => 'EEE201', 'course_title' => 'Circuits', 'department' => 'EEE', 'semester_id' => $s2->id, 'file_path' => 'notes/b.pdf', 'status' => 'approved', 'credit_price' => 5]);
        $highRated = Note::create(['uploader_id' => $u->id, 'title' => 'High Rated', 'course_no' => 'CSE301', 'course_title' => 'Algorithms', 'department' => 'CSE', 'semester_id' => $s1->id, 'file_path' => 'notes/c.pdf', 'status' => 'approved', 'credit_price' => 0]);

        Review::create(['note_id' => $free->id, 'user_id' => $rater->id, 'rating' => 2, 'comment' => 'ok']);
        Review::create(['note_id' => $highRated->id, 'user_id' => $rater->id, 'rating' => 5, 'comment' => 'excellent']);
    }

    public function test_filters_by_department(): void
    {
        $r = $this->actingAs(User::factory()->create())->get(route('browse.index', ['department' => 'CSE']));
        $r->assertSee('Free CSE Note')->assertSee('High Rated')->assertDontSee('Paid EEE Note');
    }

    public function test_filters_by_semester(): void
    {
        $s1 = Semester::where('name', 'Semester 1')->first();
        $r = $this->actingAs(User::factory()->create())->get(route('browse.index', ['semester_id' => $s1->id]));
        $r->assertSee('Free CSE Note')->assertSee('High Rated')->assertDontSee('Paid EEE Note');
    }

    public function test_filters_by_price_free(): void
    {
        $r = $this->actingAs(User::factory()->create())->get(route('browse.index', ['price' => 'free']));
        $r->assertSee('Free CSE Note')->assertSee('High Rated')->assertDontSee('Paid EEE Note');
    }

    public function test_filters_by_price_paid(): void
    {
        $r = $this->actingAs(User::factory()->create())->get(route('browse.index', ['price' => 'paid']));
        $r->assertSee('Paid EEE Note')->assertDontSee('Free CSE Note');
    }

    public function test_filters_by_min_rating(): void
    {
        $r = $this->actingAs(User::factory()->create())->get(route('browse.index', ['min_rating' => 4]));
        $r->assertSee('High Rated')->assertDontSee('Free CSE Note');
    }

    public function test_combines_search_and_filters(): void
    {
        $r = $this->actingAs(User::factory()->create())->get(route('browse.index', ['q' => 'Note', 'department' => 'CSE']));
        $r->assertSee('Free CSE Note')->assertDontSee('Paid EEE Note');
    }
}
