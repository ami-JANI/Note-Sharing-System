<?php

namespace App\Http\Controllers;

use App\Models\PreviousQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PreviousQuestionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'year' => ['required', 'string', 'max:9'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,ppt,pptx', 'max:10240'],
        ]);

        $path = $request->file('file')->store('previous-questions', 'public');

        PreviousQuestion::create([
            'subject_id' => $validated['subject_id'],
            'uploader_id' => $request->user()->id,
            'year' => $validated['year'],
            'file_path' => $path,
        ]);

        return back()->with('status', 'Previous question uploaded.');
    }

    public function download(PreviousQuestion $previousQuestion)
    {
        return Storage::disk('public')->download($previousQuestion->file_path, $previousQuestion->year);
    }
}
