<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberResourceInteraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_resource_id',
        'user_id',
        'status',
        'last_viewed_at',
        'view_count',
        'metadata',
    ];

    protected $casts = [
        'last_viewed_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function resource()
    {
        return $this->belongsTo(MemberResource::class, 'member_resource_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}