<?php

namespace App\Http\Controllers;

use App\Models\Subject;

class SubjectController extends Controller
{
    public function show(Subject $subject)
    {
        $notes = $subject->notes()->where('status', 'approved')->latest()->get();
        $previousQuestions = $subject->previousQuestions;

        return view('subjects.show', compact('subject', 'notes', 'previousQuestions'));
    }
}
