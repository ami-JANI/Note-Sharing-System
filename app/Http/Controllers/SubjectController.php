<?php

namespace App\Http\Controllers;

use App\Models\Subject;

class SubjectController extends Controller
{
    public function show(Subject $subject)
    {
        $notes = $subject->notes;
        $previousQuestions = $subject->previousQuestions;

        return view('subjects.show', compact('subject', 'notes', 'previousQuestions'));
    }
}
