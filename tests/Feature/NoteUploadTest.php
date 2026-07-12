<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NoteUploadTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Subject $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $semester = Semester::create(['name' => 'Semester 1', 'order' => 1]);
        $this->subject = Subject::create([
            'semester_id' => $semester->id,
            'code' => 'TEST101',
            'name' => 'Test Subject',
        ]);
    }

    public function test_authenticated_user_can_upload_a_note(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('notes.pdf', 100);

        $response = $this->actingAs($this->user)->post(route('notes.store'), [
            'subject_id' => $this->subject->id,
            'title' => 'Introduction to Algorithms',
            'course_no' => 'CSE301',
            'course_title' => 'Algorithms',
            'description' => 'Chapter 1 notes',
            'file' => $file,
        ]);

        $response->assertSessionHas('status', 'Note uploaded and awaiting admin approval.');
        $response->assertRedirect();

        $this->assertDatabaseHas('notes', [
            'subject_id' => $this->subject->id,
            'uploader_id' => $this->user->id,
            'title' => 'Introduction to Algorithms',
            'course_no' => 'CSE301',
            'course_title' => 'Algorithms',
            'description' => 'Chapter 1 notes',
        ]);
    }

    public function test_uploaded_note_is_hidden_until_admin_approves(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('notes.pdf', 100);

        $this->actingAs($this->user)->post(route('notes.store'), [
            'subject_id' => $this->subject->id,
            'title' => 'Data Structures Review',
            'course_no' => 'CSE201',
            'course_title' => 'Data Structures',
            'description' => null,
            'file' => $file,
        ]);

        $hidden = $this->actingAs($this->user)->get(route('subjects.show', $this->subject));
        $hidden->assertDontSee('Data Structures Review');

        Note::first()->update(['status' => 'approved']);

        $visible = $this->actingAs($this->user)->get(route('subjects.show', $this->subject));
        $visible->assertOk();
        $visible->assertSee('Data Structures Review');
        $visible->assertSee(route('notes.download', Note::first()));
    }

    public function test_authenticated_user_can_download_a_note(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('notes.pdf', 100);
        $this->actingAs($this->user)->post(route('notes.store'), [
            'subject_id' => $this->subject->id,
            'title' => 'Download Test',
            'course_no' => 'CSE101',
            'course_title' => 'Programming',
            'file' => $file,
        ]);

        $note = Note::first();

        $response = $this->actingAs($this->user)->get(route('notes.download', $note));

        $response->assertOk();
        $response->assertHeader('content-disposition', 'attachment; filename="Download Test"');
    }

    public function test_unauthenticated_user_cannot_upload_note(): void
    {
        $response = $this->post(route('notes.store'), [
            'subject_id' => $this->subject->id,
            'title' => 'Should not work',
            'course_no' => 'CSE101',
            'course_title' => 'Programming',
            'file' => UploadedFile::fake()->create('test.pdf'),
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_note_upload_rejects_invalid_file_type(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('notes.exe', 100);

        $response = $this->actingAs($this->user)->post(route('notes.store'), [
            'subject_id' => $this->subject->id,
            'title' => 'Invalid file',
            'course_no' => 'CSE101',
            'course_title' => 'Programming',
            'file' => $file,
        ]);

        $response->assertSessionHasErrors('file');
        $this->assertDatabaseCount('notes', 0);
    }

    public function test_note_upload_rejects_oversized_file(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('notes.pdf', 12000);

        $response = $this->actingAs($this->user)->post(route('notes.store'), [
            'subject_id' => $this->subject->id,
            'title' => 'Oversized file',
            'course_no' => 'CSE101',
            'course_title' => 'Programming',
            'file' => $file,
        ]);

        $response->assertSessionHasErrors('file');
        $this->assertDatabaseCount('notes', 0);
    }

    public function test_note_upload_requires_title(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('notes.pdf', 100);

        $response = $this->actingAs($this->user)->post(route('notes.store'), [
            'subject_id' => $this->subject->id,
            'course_no' => 'CSE101',
            'course_title' => 'Programming',
            'file' => $file,
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_note_upload_requires_course_no(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('notes.pdf', 100);

        $response = $this->actingAs($this->user)->post(route('notes.store'), [
            'subject_id' => $this->subject->id,
            'title' => 'Missing course no',
            'course_title' => 'Programming',
            'file' => $file,
        ]);

        $response->assertSessionHasErrors('course_no');
    }

    public function test_note_upload_requires_course_title(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('notes.pdf', 100);

        $response = $this->actingAs($this->user)->post(route('notes.store'), [
            'subject_id' => $this->subject->id,
            'title' => 'Missing course title',
            'course_no' => 'CSE101',
            'file' => $file,
        ]);

        $response->assertSessionHasErrors('course_title');
    }

    public function test_note_upload_rejects_ppt(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('notes.ppt', 100);

        $response = $this->actingAs($this->user)->post(route('notes.store'), [
            'subject_id' => $this->subject->id,
            'title' => 'PPT should fail',
            'course_no' => 'CSE101',
            'course_title' => 'Programming',
            'file' => $file,
        ]);

        $response->assertSessionHasErrors('file');
        $this->assertDatabaseCount('notes', 0);
    }

    public function test_note_upload_allows_docx(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('notes.docx', 100);

        $response = $this->actingAs($this->user)->post(route('notes.store'), [
            'subject_id' => $this->subject->id,
            'title' => 'DOCX Allowed',
            'course_no' => 'CSE101',
            'course_title' => 'Programming',
            'file' => $file,
        ]);

        $response->assertSessionHas('status', 'Note uploaded and awaiting admin approval.');
        $this->assertDatabaseHas('notes', ['title' => 'DOCX Allowed']);
    }
}
