<?php

namespace App\Services;

use App\Models\Note;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

class NotePreviewService
{
    /**
     * Number of leading pages to render for the detail-page preview.
     */
    public const PREVIEW_PAGES = 2;

    /**
     * Generate preview images for a note's uploaded file.
     *
     * Renders the first pages of a PDF to PNGs via Ghostscript. If the file
     * isn't a PDF, or Ghostscript isn't installed, this is a no-op and the
     * note simply keeps its placeholder (cards fall back to an icon).
     */
    public function generate(Note $note): void
    {
        if (! $this->isPdf($note->file_path)) {
            return;
        }

        $gs = $this->ghostscriptBinary();
        if (! $gs) {
            return;
        }

        $disk = Storage::disk('public');
        $sourcePath = $disk->path($note->file_path);

        if (! is_file($sourcePath)) {
            return;
        }

        $relativeDir = "previews/{$note->id}";
        $disk->makeDirectory($relativeDir);
        $absoluteDir = $disk->path($relativeDir);

        $pages = [];
        for ($page = 1; $page <= self::PREVIEW_PAGES; $page++) {
            $relative = "{$relativeDir}/page-{$page}.png";
            $absolute = $disk->path($relative);

            $process = new Process([
                $gs,
                '-sDEVICE=png16m',
                '-dNOPAUSE',
                '-dBATCH',
                '-dSAFER',
                '-r150',
                "-dFirstPage={$page}",
                "-dLastPage={$page}",
                "-sOutputFile={$absolute}",
                $sourcePath,
            ]);

            $process->run();

            if ($process->isSuccessful() && is_file($absolute)) {
                $pages[] = $relative;
            } else {
                // Page doesn't exist (short doc) or render failed — stop here.
                break;
            }
        }

        if ($pages !== []) {
            $note->update([
                'preview_image_path' => $pages[0],
                'preview_pages' => $pages,
            ]);
        }
    }

    protected function isPdf(string $path): bool
    {
        return strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'pdf';
    }

    /**
     * Locate a Ghostscript binary, or null if it isn't installed.
     */
    protected function ghostscriptBinary(): ?string
    {
        $configured = config('services.ghostscript.bin');
        if ($configured && is_file($configured)) {
            return $configured;
        }

        return (new ExecutableFinder())->find('gswin64c')
            ?? (new ExecutableFinder())->find('gswin32c')
            ?? (new ExecutableFinder())->find('gs');
    }
}
