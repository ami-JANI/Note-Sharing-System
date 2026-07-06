<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['semester_id', 'code', 'name'];

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class)->latest();
    }

    public function previousQuestions()
    {
        return $this->hasMany(PreviousQuestion::class)->latest();
    }
}
