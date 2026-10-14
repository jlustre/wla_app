<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * How a new member joins this company. WLA's own invite link is always
     * a fixed, ref-code-tracked link (see InviteCode) — these only describe
     * the affiliated company's own external registration page.
     */
    public const SIGNUP_LINK_TYPE_DIRECT = 'link';
    public const SIGNUP_LINK_TYPE_SPONSOR_ENTRY = 'sponsor_entry';

    public const SIGNUP_LINK_TYPES = [
        self::SIGNUP_LINK_TYPE_DIRECT => 'Single registration link (no sponsor tracking)',
        self::SIGNUP_LINK_TYPE_SPONSOR_ENTRY => 'New member manually enters sponsor username or ID',
    ];

    /**
     * Which identifier a sponsor should hand out when signup_link_type is
     * 'sponsor_entry', keyed by the value stored in signup_sponsor_field.
     */
    public const SPONSOR_FIELD_USERNAME = 'username';
    public const SPONSOR_FIELD_ID = 'id';

    public const SPONSOR_FIELDS = [
        self::SPONSOR_FIELD_USERNAME => 'Username',
        self::SPONSOR_FIELD_ID => 'Member ID',
    ];

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
        'signup_link_type',
        'signup_sponsor_field',
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

    public function memberAffiliations()
    {
        return $this->hasMany(MemberCompanyAffiliation::class);
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function usesSponsorEntry(): bool
    {
        return $this->signup_link_type === self::SIGNUP_LINK_TYPE_SPONSOR_ENTRY;
    }

    /**
     * The label for whichever identifier this company's registration form
     * asks new members to enter ("Username" or "Member ID").
     */
    public function sponsorFieldLabel(): string
    {
        return self::SPONSOR_FIELDS[$this->signup_sponsor_field] ?? self::SPONSOR_FIELDS[self::SPONSOR_FIELD_USERNAME];
    }

    /**
     * The value an existing member should hand out as "their sponsor info"
     * when inviting someone to this company, per its configured field.
     */
    public function sponsorValueFor(User $user): string
    {
        return $this->signup_sponsor_field === self::SPONSOR_FIELD_ID
            ? (string) $user->id
            : $user->username;
    }

    public function reviews()
    {
        return $this->hasMany(CompanyReview::class);
    }

    /**
     * Average star rating (1-5, rounded to 1 decimal). Uses the eager-loaded
     * `reviews_avg_rating` attribute from withAvg('reviews', 'rating') when
     * present, otherwise falls back to a live query.
     */
    public function getAverageRatingAttribute(): float
    {
        $average = $this->attributes['reviews_avg_rating'] ?? $this->reviews()->avg('rating');

        return $average ? round((float) $average, 1) : 0.0;
    }

    /**
     * Total number of reviews. Uses the eager-loaded `reviews_count`
     * attribute from withCount('reviews') when present, otherwise falls
     * back to a live query.
     */
    public function getTotalReviewsAttribute(): int
    {
        return (int) ($this->attributes['reviews_count'] ?? $this->reviews()->count());
    }

    /**
     * Count and percentage of reviews for each overall star value, 5 down to 1
     * (the decimal overall rating is rounded to the nearest whole star).
     */
    public function ratingBreakdown(): array
    {
        $counts = $this->reviews()
            ->selectRaw('round(rating) as star, count(*) as total')
            ->groupBy('star')
            ->pluck('total', 'star');
        $total = $counts->sum();

        $breakdown = [];
        for ($star = 5; $star >= 1; $star--) {
            $count = (int) ($counts[$star] ?? 0);
            $breakdown[$star] = [
                'count' => $count,
                'percent' => $total > 0 ? (int) round($count / $total * 100) : 0,
            ];
        }

        return $breakdown;
    }

    /**
     * Average rating per review category (Compensation Plan, Products &
     * Services, etc.), keyed the same way as CompanyReview::CATEGORIES.
     */
    public function categoryAverages(): array
    {
        $columns = array_keys(CompanyReview::CATEGORIES);

        $averages = $this->reviews()
            ->selectRaw(collect($columns)->map(fn ($column) => "avg($column) as $column")->implode(', '))
            ->first();

        $breakdown = [];
        foreach (CompanyReview::CATEGORIES as $column => $label) {
            $value = $averages?->{$column};
            $breakdown[$column] = [
                'label' => $label,
                'average' => $value ? round((float) $value, 1) : 0.0,
            ];
        }

        return $breakdown;
    }
}
