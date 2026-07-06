<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreviousQuestion extends Model
{
    protected $fillable = ['subject_id', 'uploader_id', 'year', 'file_path'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }
}
