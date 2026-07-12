<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use App\Services\NotePreviewService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NotePreviewTest extends TestCase
{
    use RefreshDatabase;

    private function makeNote(string $file = 'notes/x.pdf'): Note
    {
        $semester = Semester::create(['name' => 'Semester 1', 'order' => 0]);
        $subject = Subject::create(['semester_id' => $semester->id, 'code' => 'CSE101', 'name' => 'Programming']);
        $uploader = User::factory()->create();

        return Note::create([
            'subject_id' => $subject->id,
            'uploader_id' => $uploader->id,
            'title' => 'Preview Note',
            'course_no' => 'CSE101',
            'course_title' => 'Programming',
            'file_path' => $file,
            'status' => 'approved',
        ]);
    }

    public function test_preview_generation_is_a_noop_for_non_pdf_files(): void
    {
        $note = $this->makeNote('notes/x.docx');

        (new NotePreviewService())->generate($note);

        $this->assertNull($note->fresh()->preview_image_path);
        $this->assertNull($note->fresh()->preview_pages);
    }

    public function test_preview_generation_does_not_break_when_ghostscript_is_absent(): void
    {
        // Force the "no ghostscript" path via an invalid configured binary.
        config(['services.ghostscript.bin' => 'C:\\path\\that\\does\\not\\exist\\gs.exe']);

        $note = $this->makeNote('notes/missing.pdf');

        (new NotePreviewService())->generate($note);

        // No preview produced, and crucially no exception thrown.
        $this->assertNull($note->fresh()->preview_image_path);
    }

    public function test_upload_still_succeeds_without_preview_tooling(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $semester = Semester::create(['name' => 'Semester 1', 'order' => 0]);
        $subject = Subject::create(['semester_id' => $semester->id, 'code' => 'CSE101', 'name' => 'Programming']);

        $response = $this->actingAs($user)->post(route('notes.store'), [
            'subject_id' => $subject->id,
            'title' => 'Uploaded Note',
            'course_no' => 'CSE101',
            'course_title' => 'Programming',
            'file' => UploadedFile::fake()->create('note.pdf', 100),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('notes', ['title' => 'Uploaded Note']);
    }

    public function test_note_detail_page_renders_with_preview_pages(): void
    {
        $note = $this->makeNote();
        $note->update(['preview_pages' => ['previews/1/page-1.png', 'previews/1/page-2.png']]);

        $viewer = User::factory()->create();

        $this->actingAs($viewer)->get(route('notes.show', $note))
            ->assertOk()
            ->assertSee('previews/1/page-1.png');
    }
}
