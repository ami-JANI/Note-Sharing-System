<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Review;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Http\Request;

class BrowseController extends Controller
{
    public function index(Request $request)
    {
        $query = Note::where('status', 'approved')
            ->where('hidden', false)
            ->with(['uploader', 'subject']);

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($qry) use ($q) {
                $qry->where('title', 'like', "%{$q}%")
                    ->orWhere('course_title', 'like', "%{$q}%")
                    ->orWhere('course_no', 'like', "%{$q}%")
                    ->orWhere('department', 'like', "%{$q}%")
                    ->orWhereHas('semester', fn($s) => $s->where('name', 'like', "%{$q}%"));
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->input('department'));
        }

        if ($request->filled('course')) {
            $query->where('course_no', $request->input('course'));
        }

        if ($request->filled('semester_id')) {
            $query->where('semester_id', $request->input('semester_id'));
        }

        if ($request->filled('price')) {
            match ($request->input('price')) {
                'free' => $query->where('credit_price', 0),
                'paid' => $query->where('credit_price', '>', 0),
                default => null,
            };
        }

        if ($request->filled('min_rating')) {
            $min = (int) $request->input('min_rating');
            $query->whereRaw('(SELECT AVG(rating) FROM reviews WHERE note_id = notes.id AND is_hidden = 0) >= ?', [$min]);
        }

        $notes = $query->latest()->paginate(20)->withQueryString();

        $view = auth()->check() ? 'browse.index' : 'browse.guest';

        $visibleNotes = Note::where('status', 'approved')->where('hidden', false);

        // Aggregate on uploader_id only (not users.*) so this stays valid
        // under MySQL's default ONLY_FULL_GROUP_BY mode -- selecting
        // non-aggregated, non-grouped-by columns like users.name errors
        // there even though SQLite (used in tests) silently allows it.
        $topUploaderRow = Note::join('reviews', 'reviews.note_id', '=', 'notes.id')
            ->where('reviews.is_hidden', false)
            ->where('reviews.created_at', '>=', now()->subDays(7))
            ->selectRaw('notes.uploader_id, AVG(reviews.rating) as avg_rating')
            ->groupBy('notes.uploader_id')
            ->orderByDesc('avg_rating')
            ->first();

        if (! $topUploaderRow) {
            $topUploaderRow = Note::join('reviews', 'reviews.note_id', '=', 'notes.id')
                ->where('reviews.is_hidden', false)
                ->selectRaw('notes.uploader_id, AVG(reviews.rating) as avg_rating')
                ->groupBy('notes.uploader_id')
                ->orderByDesc('avg_rating')
                ->first();
        }

        $topUploader = null;
        if ($topUploaderRow) {
            $topUploader = User::find($topUploaderRow->uploader_id);
            $topUploader->avg_rating = $topUploaderRow->avg_rating;
        }

        return view($view, [
            'notes' => $notes,
            'topUploader' => $topUploader,
            // Filter options only list departments/courses that at least one
            // visible note actually uses, not the full master list — no point
            // offering a department with zero results.
            'departments' => (clone $visibleNotes)->whereNotNull('department')->distinct()->orderBy('department')->pluck('department'),
            'courses' => (clone $visibleNotes)->whereNotNull('course_no')->distinct()->orderBy('course_no')->pluck('course_no'),
            'semesters' => Semester::orderBy('order')->get(),
        ]);
    }
}
