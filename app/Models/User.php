<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department',
        'batch',
        'credits',
        'status',
        'photo',
        'roll',
        'phone',
        'current_semester_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function getIsSuspendedAttribute(): bool
    {
        return $this->status === 'suspended';
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'uploader_id');
    }

    public function previousQuestions()
    {
        return $this->hasMany(PreviousQuestion::class, 'uploader_id');
    }

    public function currentSemester()
    {
        return $this->belongsTo(Semester::class, 'current_semester_id');
    }

    public function purchases()
    {
        return $this->hasMany(NotePurchase::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Notes this user has downloaded (distinct, most recent first).
     */
    public function downloadedNotes()
    {
        return $this->belongsToMany(Note::class, 'note_downloads', 'user_id', 'note_id')->withTimestamps();
    }

    /**
     * Uploaders this user has favorited.
     */
    public function favoriteUploaders()
    {
        return $this->belongsToMany(User::class, 'favorites', 'user_id', 'uploader_id')->withTimestamps();
    }

    /**
     * Users who have favorited this uploader.
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'uploader_id', 'user_id')->withTimestamps();
    }

    public function hasFavorited(User $uploader): bool
    {
        return $this->favoriteUploaders()->whereKey($uploader->getKey())->exists();
    }
}
