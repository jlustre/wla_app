<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CompanyReview;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompanyReviewDemoSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::all();
        $userIds = User::query()->inRandomOrder()->limit(30)->pluck('id');

        if ($companies->isEmpty() || $userIds->isEmpty()) {
            return;
        }

        $sampleReviews = [
            5 => [
                'Joining was the best decision I made this year — the sponsorship structure is crystal clear and support has been fantastic.',
                'Compensation plan pays exactly as described. My upline has been responsive every step of the way.',
                'Great products, great training resources, and onboarding only took a few minutes.',
                null,
            ],
            4 => [
                'Solid opportunity overall. Would love to see more webinars, but the core platform works well.',
                'Good experience so far. Backoffice could be a bit more intuitive, but support answers quickly.',
                null,
            ],
            3 => [
                'It\'s fine — does what it says. Nothing has blown me away yet, but no complaints either.',
                'Average experience. Still waiting to see results from my downline before I give a higher rating.',
            ],
            2 => [
                'Slower to get started than I expected. The compensation plan took a while to understand.',
                null,
            ],
            1 => [
                'Had trouble getting my backoffice access set up and support took a few days to respond.',
            ],
        ];

        $rows = [];
        $now = now();
        $weightedRatings = [5, 5, 5, 4, 4, 3, 2, 1];
        $categoryColumns = array_keys(CompanyReview::CATEGORIES);

        foreach ($companies as $company) {
            $reviewerIds = $userIds->shuffle()->take(random_int(4, 8));

            foreach ($reviewerIds as $userId) {
                // Pick an overall sentiment, then jitter each category ±1 around it
                // so category scores feel independent instead of all identical.
                $base = $weightedRatings[array_rand($weightedRatings)];

                $categoryRatings = [];
                foreach ($categoryColumns as $column) {
                    $categoryRatings[$column] = max(1, min(5, $base + random_int(-1, 1)));
                }

                $overall = round(array_sum($categoryRatings) / count($categoryRatings), 1);
                $texts = $sampleReviews[$base];

                $rows[] = [
                    'company_id' => $company->id,
                    'user_id' => $userId,
                    ...$categoryRatings,
                    'rating' => $overall,
                    'review' => $texts[array_rand($texts)],
                    'created_at' => $now->copy()->subDays(random_int(1, 120)),
                    'updated_at' => $now,
                ];
            }
        }

        foreach (array_chunk($rows, 50) as $chunk) {
            \DB::table('company_reviews')->insertOrIgnore($chunk);
        }
    }
}
