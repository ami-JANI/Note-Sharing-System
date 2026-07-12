<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['note_id', 'user_id', 'rating', 'comment'];

    protected function casts(): array
    {
        return ['is_hidden' => 'boolean'];
    }

    public function note()
    {
        return $this->belongsTo(Note::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
