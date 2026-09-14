<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberEventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_event_id',
        'user_id',
        'status',
        'reserved_at',
        'attended_at',
        'metadata',
    ];

    protected $casts = [
        'reserved_at' => 'datetime',
        'attended_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function event()
    {
        return $this->belongsTo(MemberEvent::class, 'member_event_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}