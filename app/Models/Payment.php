<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['user_id', 'credits_purchased', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
