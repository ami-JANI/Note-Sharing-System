<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = ['subject_id', 'uploader_id', 'title', 'course_no', 'course_title', 'description', 'file_path', 'credit_price', 'status'];

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

    public function visibleReviews()
    {
        return $this->reviews()->where('is_hidden', false);
    }

    public function averageRating(): float
    {
        return (float) $this->visibleReviews()->average('rating') ?: 0.0;
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
