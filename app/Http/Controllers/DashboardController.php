<?php

namespace App\Http\Controllers;

use App\Models\Semester;

class DashboardController extends Controller
{
    public function index()
    {
        $semesters = Semester::orderBy('order')->get();

        return view('dashboard', compact('semesters'));
    }
}
