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

    public function test_upload_page_is_reachable(): void
    {
        $this->actingAs($this->user)->get(route('notes.create'))->assertOk();
    }

    public function test_guest_cannot_reach_upload_page(): void
    {
        $this->get(route('notes.create'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_upload_a_note(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('notes.pdf', 100);

        $response = $this->actingAs($this->user)->post(route('notes.store'), [
            'title' => 'Introduction to Algorithms',
            'course_no' => 'CSE301',
            'course_title' => 'Algorithms',
            'description' => 'Chapter 1 notes',
            'file' => $file,
        ]);

        $response->assertSessionHas('status', 'Note uploaded and awaiting admin approval.');
        $response->assertRedirect();

        $this->assertDatabaseHas('notes', [
            'uploader_id' => $this->user->id,
            'title' => 'Introduction to Algorithms',
            'course_no' => 'CSE301',
            'course_title' => 'Algorithms',
            'description' => 'Chapter 1 notes',
        ]);
    }

    public function test_uploaded_note_is_hidden_from_browse_until_admin_approves(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('notes.pdf', 100);

        $this->actingAs($this->user)->post(route('notes.store'), [
            'title' => 'Data Structures Review',
            'course_no' => 'CSE201',
            'course_title' => 'Data Structures',
            'description' => null,
            'file' => $file,
        ]);

        $hidden = $this->actingAs($this->user)->get(route('browse.index'));
        $hidden->assertDontSee('Data Structures Review');

        Note::first()->update(['status' => 'approved']);

        $visible = $this->actingAs($this->user)->get(route('browse.index'));
        $visible->assertOk();
        $visible->assertSee('Data Structures Review');
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
        $response->assertHeader('content-disposition', 'attachment; filename="Download Test.pdf"');
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

    public function test_credit_milestone_every_5_uploads(): void
    {
        Storage::fake('public');

        $this->assertEquals(0, $this->user->fresh()->credits);

        // Upload 4 notes — no credit change.
        for ($i = 1; $i <= 4; $i++) {
            $file = UploadedFile::fake()->createWithContent("note{$i}.pdf", "content $i");
            $this->actingAs($this->user)->post(route('notes.store'), [
                'title' => "Note $i",
                'course_no' => 'CSE101',
                'course_title' => 'Programming',
                'file' => $file,
            ]);
            $this->assertEquals(0, $this->user->fresh()->credits);
        }

        // 5th upload — +10 credits.
        $file5 = UploadedFile::fake()->createWithContent('note5.pdf', 'content 5');
        $this->actingAs($this->user)->post(route('notes.store'), [
            'title' => 'Note 5',
            'course_no' => 'CSE101',
            'course_title' => 'Programming',
            'file' => $file5,
        ]);
        $this->assertEquals(10, $this->user->fresh()->credits);

        // Upload 6th–9th — no change.
        for ($i = 6; $i <= 9; $i++) {
            $file = UploadedFile::fake()->createWithContent("note{$i}.pdf", "content $i");
            $this->actingAs($this->user)->post(route('notes.store'), [
                'title' => "Note $i",
                'course_no' => 'CSE101',
                'course_title' => 'Programming',
                'file' => $file,
            ]);
            $this->assertEquals(10, $this->user->fresh()->credits);
        }

        // 10th upload — +10 again.
        $file10 = UploadedFile::fake()->createWithContent('note10.pdf', 'content 10');
        $this->actingAs($this->user)->post(route('notes.store'), [
            'title' => 'Note 10',
            'course_no' => 'CSE101',
            'course_title' => 'Programming',
            'file' => $file10,
        ]);
        $this->assertEquals(20, $this->user->fresh()->credits);
    }

    public function test_duplicate_file_is_rejected_by_hash(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('duplicate.pdf', 100);

        $this->actingAs($this->user)->post(route('notes.store'), [
            'title' => 'First upload',
            'course_no' => 'CSE101',
            'course_title' => 'Programming',
            'file' => $file,
        ]);

        $this->assertDatabaseHas('notes', ['title' => 'First upload']);

        $response = $this->actingAs($this->user)->post(route('notes.store'), [
            'title' => 'Second upload',
            'course_no' => 'CSE101',
            'course_title' => 'Programming',
            'file' => $file,
        ]);

        $response->assertSessionHasErrors('file');
        $this->assertDatabaseMissing('notes', ['title' => 'Second upload']);
        $this->assertDatabaseCount('notes', 1);
    }

    public function test_different_files_both_succeed(): void
    {
        Storage::fake('public');

        $fileA = UploadedFile::fake()->createWithContent('a.pdf', 'unique content A for hash test');
        $fileB = UploadedFile::fake()->createWithContent('b.pdf', 'unique content B for hash test');

        $this->actingAs($this->user)->post(route('notes.store'), [
            'title' => 'File A',
            'course_no' => 'CSE101',
            'course_title' => 'Programming',
            'file' => $fileA,
        ]);

        $this->actingAs($this->user)->post(route('notes.store'), [
            'title' => 'File B',
            'course_no' => 'CSE101',
            'course_title' => 'Programming',
            'file' => $fileB,
        ]);

        $this->assertDatabaseHas('notes', ['title' => 'File A']);
        $this->assertDatabaseHas('notes', ['title' => 'File B']);
        $this->assertDatabaseCount('notes', 2);
    }
}
