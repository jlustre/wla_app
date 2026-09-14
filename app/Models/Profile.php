<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
        'avatar',
        'phone_number',
        'city',
        'membership_started_at',
        'invite_code',
        'theme_preference',
        'completion_percentage',
        'notification_preferences',
        'privacy_preferences',
    ];

    protected $casts = [
        'membership_started_at' => 'datetime',
        'notification_preferences' => 'array',
        'privacy_preferences' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
