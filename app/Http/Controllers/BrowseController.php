<?php

namespace App\Http\Controllers;

use App\Models\Note;
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
                    ->orWhere('course_no', 'like', "%{$q}%");
            });
        }

        $notes = $query->latest()->paginate(20)->withQueryString();

        // Guests get a login-prompt layout; authenticated users get the full app view.
        $view = auth()->check() ? 'browse.index' : 'browse.guest';

        return view($view, compact('notes'));
    }
}
