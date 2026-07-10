<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = ['subject_id', 'uploader_id', 'title', 'description', 'file_path', 'credit_price'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function purchases()
    {
        return $this->hasMany(NotePurchase::class);
    }
}
