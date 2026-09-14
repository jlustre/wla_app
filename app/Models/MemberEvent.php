<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'starts_at',
        'ends_at',
        'location',
        'audience',
        'cta_label',
        'is_published',
        'metadata',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_published' => 'boolean',
        'metadata' => 'array',
    ];

    public function registrations()
    {
        return $this->hasMany(MemberEventRegistration::class);
    }
}