<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invitation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'inviter_id',
        'sponsor_id',
        'accepted_user_id',
        'email',
        'phone',
        'invite_code',
        'invite_link',
        'status',
        'sent_at',
        'accepted_at',
        'expires_at',
        'last_viewed_at',
        'metadata',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'accepted_at' => 'datetime',
        'expires_at' => 'datetime',
        'last_viewed_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function sponsor()
    {
        return $this->belongsTo(User::class, 'sponsor_id');
    }

    public function acceptedUser()
    {
        return $this->belongsTo(User::class, 'accepted_user_id');
    }
}