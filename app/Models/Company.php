<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'category',
        'website_link',
        'short_description',
        'full_description',
        'banner',
        'comp_plan_link',
        'signup_link',
        'backoffice_link',
        'intro_video_link',
        'webinar_link',
        'location',
        'phone',
        'tagline',
        'ceo_name',
        'theme_id',
        'status',
        'primary_color',
        'secondary_color',
        'highlight_color',
        'background_color',
        'style_meta',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'style_meta' => 'array',
    ];

    public function dashboardContents()
    {
        return $this->hasMany(DashboardContent::class);
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }
}
