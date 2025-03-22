<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSocialite extends Model
{
    protected $casts = [
        'data' => 'array',
        'token_expires_at' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'provider',
        'social_id',
        'nickname',
        'name',
        'email',
        'avatar',
        'data',
        'token',
        'refresh_token',
        'token_expires_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
