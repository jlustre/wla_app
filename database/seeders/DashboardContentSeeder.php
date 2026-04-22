<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DashboardContent;
use App\Models\Company;

class DashboardContentSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::where('slug', 'example-insurance-co')->first();
        if (!$company) {
            return;
        }

        $sections = [
            [
                'section' => 'hero',
                'title' => 'Company Opportunity Overview',
                'content' => 'Access everything you need for this affiliated company in one place—overview, products, compensation plan, important links, resources, and your current position status inside Wealth Legacy Alliance.',
                'order' => 1,
            ],
            [
                'section' => 'overview',
                'title' => 'About This Company',
                'content' => 'Example Insurance Co. is an affiliated company inside the Wealth Legacy Alliance ecosystem. It provides financial protection solutions designed to help families prepare for the future, protect income, and build long-term financial confidence.',
                'order' => 2,
            ],
            [
                'section' => 'products',
                'title' => 'What This Company Offers',
                'content' => 'Life Insurance, Indexed Universal Life, Annuities',
                'order' => 3,
            ],
            [
                'section' => 'compensation',
                'title' => 'How Earnings Work in This Company',
                'content' => 'This company follows its own compensation plan. That means commissions, overrides, bonuses, qualifications, ranks, and payout structures are all managed by the company itself—not by WLA.',
                'order' => 4,
            ],
            [
                'section' => 'resources',
                'title' => 'Learning Materials',
                'content' => 'Getting Started Guide, Training Videos, PDF Documents, Webinars & Events',
                'order' => 5,
            ],
        ];

        foreach ($sections as $section) {
            DashboardContent::create([
                'company_id' => $company->id,
                ...$section,
            ]);
        }
    }
}
