<?php

namespace Tests\Feature;

use App\Models\PreviousQuestion;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PreviousQuestionUploadTest extends TestCase
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

    public function test_authenticated_user_can_upload_previous_question(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('pq.pdf', 100);

        $response = $this->actingAs($this->user)->post(route('previous-questions.store'), [
            'subject_id' => $this->subject->id,
            'year' => '2024',
            'file' => $file,
        ]);

        $response->assertSessionHas('status', 'Previous question uploaded.');
        $response->assertRedirect();

        $this->assertDatabaseHas('previous_questions', [
            'subject_id' => $this->subject->id,
            'uploader_id' => $this->user->id,
            'year' => '2024',
        ]);
    }

    public function test_uploaded_previous_question_appears_on_subject_page(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('pq.pdf', 100);

        $this->actingAs($this->user)->post(route('previous-questions.store'), [
            'subject_id' => $this->subject->id,
            'year' => '2023',
            'file' => $file,
        ]);

        $response = $this->actingAs($this->user)->get(route('subjects.show', $this->subject));

        $response->assertOk();
        $response->assertSee('2023');
    }

    public function test_authenticated_user_can_download_previous_question(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('pq.pdf', 100);
        $this->actingAs($this->user)->post(route('previous-questions.store'), [
            'subject_id' => $this->subject->id,
            'year' => '2024',
            'file' => $file,
        ]);

        $pq = PreviousQuestion::first();

        $response = $this->actingAs($this->user)->get(route('previous-questions.download', $pq));

        $response->assertOk();
        $response->assertHeader('content-disposition', 'attachment; filename=2024');
    }

    public function test_unauthenticated_user_cannot_upload_previous_question(): void
    {
        $response = $this->post(route('previous-questions.store'), [
            'subject_id' => $this->subject->id,
            'year' => '2024',
            'file' => UploadedFile::fake()->create('test.pdf'),
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_previous_question_upload_rejects_invalid_file_type(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('pq.exe', 100);

        $response = $this->actingAs($this->user)->post(route('previous-questions.store'), [
            'subject_id' => $this->subject->id,
            'year' => '2024',
            'file' => $file,
        ]);

        $response->assertSessionHasErrors('file');
        $this->assertDatabaseCount('previous_questions', 0);
    }

    public function test_previous_question_upload_requires_year(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('pq.pdf', 100);

        $response = $this->actingAs($this->user)->post(route('previous-questions.store'), [
            'subject_id' => $this->subject->id,
            'file' => $file,
        ]);

        $response->assertSessionHasErrors('year');
    }
}
