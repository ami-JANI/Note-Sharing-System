<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = ['subject_id', 'uploader_id', 'title', 'course_no', 'course_title', 'description', 'file_path', 'preview_image_path', 'preview_pages', 'credit_price', 'status', 'hidden'];

    protected $casts = [
        'preview_pages' => 'array',
        'hidden' => 'boolean',
    ];

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

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function isUnlockedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        if ($this->credit_price <= 0 || $this->uploader_id === $user->id) {
            return true;
        }

        return $this->purchases()->where('user_id', $user->id)->exists();
    }
}
