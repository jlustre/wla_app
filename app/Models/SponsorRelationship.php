<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SponsorRelationship extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'member_id',
        'sponsor_id',
        'sponsor_code',
        'is_current',
        'linked_at',
        'ended_at',
        'metadata',
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'linked_at' => 'datetime',
        'ended_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function sponsor()
    {
        return $this->belongsTo(User::class, 'sponsor_id');
    }
}