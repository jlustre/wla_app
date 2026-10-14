<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyReview extends Model
{
    use HasFactory;

    /**
     * The fixed set of categories a member rates when reviewing a company,
     * keyed by their column name. `rating` (the overall score) is always
     * derived from these — it is never set directly.
     */
    public const CATEGORIES = [
        'compensation_rating' => 'Compensation Plan',
        'products_rating' => 'Products & Services',
        'support_rating' => 'Support & Training',
        'leadership_rating' => 'Leadership & Reputation',
    ];

    protected $fillable = [
        'company_id',
        'user_id',
        'compensation_rating',
        'products_rating',
        'support_rating',
        'leadership_rating',
        'review',
    ];

    protected $casts = [
        'compensation_rating' => 'integer',
        'products_rating' => 'integer',
        'support_rating' => 'integer',
        'leadership_rating' => 'integer',
        'rating' => 'float',
    ];

    protected static function booted(): void
    {
        static::saving(function (CompanyReview $review) {
            $categoryValues = collect(array_keys(self::CATEGORIES))
                ->map(fn ($column) => $review->{$column})
                ->filter(fn ($value) => $value !== null);

            $review->rating = $categoryValues->isNotEmpty()
                ? round($categoryValues->avg(), 1)
                : 0;
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
