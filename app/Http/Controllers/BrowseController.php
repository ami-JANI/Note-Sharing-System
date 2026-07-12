<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class BrowseController extends Controller
{
    public function index(Request $request)
    {
        $query = Note::where('status', 'approved')
            ->with(['uploader', 'subject']);

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($qry) use ($q) {
                $qry->where('title', 'like', "%{$q}%")
                    ->orWhere('course_title', 'like', "%{$q}%")
                    ->orWhere('course_no', 'like', "%{$q}%");
            });
        }

        $notes = $query->latest()->paginate(20);

        return view('browse.index', compact('notes'));
    }
}
