<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Company;
use App\Models\MemberCompanyAffiliation;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

class MemberProfileDemoSeeder extends Seeder
{
    /**
     * Populates realistic profile, company affiliation, and activity data
     * for the seeded test accounts so /profile has real data to render.
     */
    public function run(): void
    {
        $member = User::where('email', 'jclustre@gmail.com')->first();

        if (! $member) {
            return;
        }

        if (method_exists($member, 'assignRole') && ! $member->hasRole('member')) {
            $member->assignRole('member');
        }

        Profile::updateOrCreate(
            ['user_id' => $member->id],
            [
                'bio' => 'Building a unified network marketing ecosystem where members recruit once, grow everywhere, and never miss an opportunity.',
                'phone_number' => '+1 (555) 018-2201',
                'city' => 'Dallas',
                'membership_started_at' => $member->created_at ?? now()->subMonths(5),
                'invite_code' => 'JLUSTRE128',
                'theme_preference' => 'dark',
                'completion_percentage' => 82,
                'notification_preferences' => ['email' => true, 'in_app' => true],
                'privacy_preferences' => ['show_phone_to_downline' => false],
            ]
        );

        $companies = Company::orderBy('id')->get()->keyBy('name');

        $affiliations = [
            ['company' => 'Blue Ocean Consulting', 'status' => 'active', 'joined_at' => now()->subDays(60)],
            ['company' => 'GreenTech Innovations', 'status' => 'active', 'joined_at' => now()->subDays(40)],
            ['company' => 'EduSmart Academy', 'status' => 'active', 'joined_at' => now()->subDays(10)],
            ['company' => 'Legal Eagle Partners', 'status' => 'pending', 'joined_at' => null],
        ];

        foreach ($affiliations as $affiliation) {
            $company = $companies->get($affiliation['company']);

            if (! $company) {
                continue;
            }

            MemberCompanyAffiliation::updateOrCreate(
                ['user_id' => $member->id, 'company_id' => $company->id],
                [
                    'status' => $affiliation['status'],
                    'joined_at' => $affiliation['joined_at'],
                ]
            );
        }

        $latestDirectMember = $member->directMembers()->latest()->first();
        $referralName = $latestDirectMember?->username ?? 'A new referral';

        $activities = [
            [
                'type' => 'success',
                'title' => 'New referral joined under your link',
                'description' => $referralName.' completed registration and is now part of your alliance team.',
                'created_at' => now()->subMinutes(12),
            ],
            [
                'type' => 'info',
                'title' => 'Joined a new affiliated company',
                'description' => 'You joined EduSmart Academy and unlocked additional team coverage.',
                'created_at' => now()->subHours(3),
            ],
            [
                'type' => 'danger',
                'title' => 'Company application pending',
                'description' => 'Your Legal Eagle Partners affiliation is awaiting approval. Review your application status.',
                'created_at' => now()->subDay(),
            ],
            [
                'type' => 'warning',
                'title' => 'Follow-up reminder',
                'description' => 'You have prospects tagged as hot or warm and ready for a second contact.',
                'created_at' => now()->subDays(2),
            ],
        ];

        foreach ($activities as $activity) {
            Activity::updateOrCreate(
                ['user_id' => $member->id, 'title' => $activity['title']],
                [
                    'type' => $activity['type'],
                    'description' => $activity['description'],
                    'created_at' => $activity['created_at'],
                    'updated_at' => $activity['created_at'],
                    'metadata' => ['source' => 'profile-demo'],
                    'ip_address' => '127.0.0.1',
                ]
            );
        }

        $admin = User::where('email', 'admin@wla.com')->first();

        if ($admin) {
            Profile::updateOrCreate(
                ['user_id' => $admin->id],
                [
                    'bio' => 'Platform administrator overseeing companies, dashboard content, and member workspace health.',
                    'phone_number' => '+1 (555) 014-9210',
                    'city' => 'Austin',
                    'membership_started_at' => $admin->created_at ?? now()->subYear(),
                    'invite_code' => 'ADMINWLA',
                    'theme_preference' => 'dark',
                    'completion_percentage' => 100,
                ]
            );
        }
    }
}
