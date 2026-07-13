<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NoteDeptSemesterTest extends TestCase
{
    use RefreshDatabase;

    public function test_upload_with_department_and_semester(): void
    {
        Storage::fake('public');

        $semester = Semester::create(['name' => 'Semester 1', 'order' => 1]);
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('notes.pdf', 100);

        $this->actingAs($user)->post(route('notes.store'), [
            'title' => 'Dept Test',
            'course_no' => 'CSE301',
            'course_title' => 'Algorithms',
            'file' => $file,
            'department' => 'CSE',
            'semester_id' => $semester->id,
        ]);

        $this->assertDatabaseHas('notes', [
            'title' => 'Dept Test',
            'department' => 'CSE',
            'semester_id' => $semester->id,
        ]);
    }

    public function test_upload_without_department_and_semester(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('notes.pdf', 100);

        $this->actingAs($user)->post(route('notes.store'), [
            'title' => 'No Dept Test',
            'course_no' => 'CSE301',
            'course_title' => 'Algorithms',
            'file' => $file,
        ]);

        $note = Note::where('title', 'No Dept Test')->first();
        $this->assertNull($note->department);
        $this->assertNull($note->semester_id);
    }

    public function test_create_page_receives_semesters_and_departments(): void
    {
        Semester::create(['name' => 'Semester 1', 'order' => 1]);
        Semester::create(['name' => 'Semester 2', 'order' => 2]);

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('notes.create'));

        $response->assertOk();
        $response->assertViewHas('semesters');
        $response->assertViewHas('departments');
        $this->assertCount(2, $response->viewData('semesters'));
        $this->assertContains('CSE', $response->viewData('departments'));
    }
}
