<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotePurchase extends Model
{
    protected $fillable = ['note_id', 'user_id', 'credits_spent'];

    public function note()
    {
        return $this->belongsTo(Note::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
