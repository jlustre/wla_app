<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class DashboardContent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'section',
        'title',
        'content',
        'image',
        'meta',
        'order',
        'element_class',
        'element_type',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'meta' => 'array',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
