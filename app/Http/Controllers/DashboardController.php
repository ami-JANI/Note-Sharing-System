<?php

namespace App\Http\Controllers;

use App\Models\Note;

class DashboardController extends Controller
{
    public function index()
    {
        $notes = Note::where('status', 'approved')
            ->with('uploader')
            ->latest()
            ->get();

        return view('dashboard', compact('notes'));
    }
}
