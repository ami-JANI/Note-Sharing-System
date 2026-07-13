<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoteDownload extends Model
{
    protected $fillable = ['user_id', 'note_id'];

    public function note()
    {
        return $this->belongsTo(Note::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
