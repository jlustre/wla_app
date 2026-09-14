<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'category',
        'audience',
        'format',
        'resource_url',
        'is_published',
        'metadata',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'metadata' => 'array',
    ];

    public function interactions()
    {
        return $this->hasMany(MemberResourceInteraction::class);
    }
}