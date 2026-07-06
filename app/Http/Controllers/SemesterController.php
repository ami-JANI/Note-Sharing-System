<?php

namespace App\Http\Controllers;

use App\Models\Semester;

class SemesterController extends Controller
{
    public function show(Semester $semester)
    {
        $subjects = $semester->subjects;

        return view('semesters.show', compact('semester', 'subjects'));
    }
}
