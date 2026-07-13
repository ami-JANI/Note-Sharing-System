<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'batch' => ['nullable', 'string', 'max:50'],
            'current_semester_id' => ['nullable', 'exists:semesters,id'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
