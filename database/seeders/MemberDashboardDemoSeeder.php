<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\AuditLog;
use App\Models\Invitation;
use App\Models\MemberEvent;
use App\Models\MemberEventRegistration;
use App\Models\MemberResource;
use App\Models\MemberResourceInteraction;
use App\Models\Profile;
use App\Models\SponsorRelationship;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MemberDashboardDemoSeeder extends Seeder
{
    public function run(): void
    {
        $sponsor = User::updateOrCreate(
            ['email' => 'sponsor-demo@wealthlegacyalliance.test'],
            [
                'username' => 'sponsor.demo',
                'password' => Hash::make('Password123!'),
                'status' => 'active',
                'rank_name' => 'Executive Sponsor',
            ]
        );

        $member = User::updateOrCreate(
            ['email' => 'member-demo@wealthlegacyalliance.test'],
            [
                'username' => 'member.demo',
                'password' => Hash::make('Password123!'),
                'status' => 'active',
                'rank_name' => 'Gold Member',
                'sponsor_id' => $sponsor->id,
            ]
        );

        if (method_exists($sponsor, 'assignRole')) {
            $sponsor->assignRole('member');
        }

        if (method_exists($member, 'assignRole')) {
            $member->assignRole('member');
        }

        Profile::updateOrCreate(
            ['user_id' => $sponsor->id],
            [
                'bio' => 'Senior sponsor helping members build a stable onboarding and education workflow.',
                'phone_number' => '+1 (555) 014-9210',
                'city' => 'Austin',
                'membership_started_at' => now()->subYear(),
                'invite_code' => 'SPONSOR-DEMO',
                'theme_preference' => 'dark',
                'completion_percentage' => 100,
                'notification_preferences' => ['email' => true, 'in_app' => true],
                'privacy_preferences' => ['show_phone_to_downline' => true],
            ]
        );

        Profile::updateOrCreate(
            ['user_id' => $member->id],
            [
                'bio' => 'Focused on compliant invite growth and education completion.',
                'phone_number' => '+1 (555) 018-2201',
                'city' => 'Dallas',
                'membership_started_at' => now()->subMonths(5),
                'invite_code' => 'MEMBER-DEMO',
                'theme_preference' => 'dark',
                'completion_percentage' => 68,
                'notification_preferences' => ['email' => true, 'in_app' => true, 'webinar_reminders' => true],
                'privacy_preferences' => ['show_phone_to_downline' => false],
            ]
        );

        SponsorRelationship::updateOrCreate(
            ['member_id' => $member->id, 'is_current' => true],
            [
                'sponsor_id' => $sponsor->id,
                'sponsor_code' => 'SP-'.str_pad((string) $sponsor->id, 4, '0', STR_PAD_LEFT),
                'linked_at' => now()->subMonths(5),
                'metadata' => ['source' => 'demo-seeder'],
            ]
        );

        foreach ([
            ['username' => 'alex.joined', 'email' => 'alex.joined@wealthlegacyalliance.test', 'created_at' => now()->subDays(2)],
            ['username' => 'janelle.team', 'email' => 'janelle.team@wealthlegacyalliance.test', 'created_at' => now()->subDays(5)],
            ['username' => 'marcus.path', 'email' => 'marcus.path@wealthlegacyalliance.test', 'created_at' => now()->subDays(8)],
        ] as $seedMember) {
            $child = User::updateOrCreate(
                ['email' => $seedMember['email']],
                [
                    'username' => $seedMember['username'],
                    'password' => Hash::make('Password123!'),
                    'status' => 'active',
                    'rank_name' => 'Member',
                    'sponsor_id' => $member->id,
                    'created_at' => $seedMember['created_at'],
                    'updated_at' => $seedMember['created_at'],
                ]
            );

            if (method_exists($child, 'assignRole')) {
                $child->assignRole('member');
            }
        }

        $invites = [
            ['email' => 'pending.one@example.test', 'status' => 'pending', 'accepted_at' => null],
            ['email' => 'pending.two@example.test', 'status' => 'sent', 'accepted_at' => null],
            ['email' => 'accepted.one@example.test', 'status' => 'accepted', 'accepted_at' => now()->subDays(9)],
        ];

        foreach ($invites as $index => $invite) {
            Invitation::updateOrCreate(
                ['invite_code' => 'MEMBER-DEMO-'.($index + 1)],
                [
                    'inviter_id' => $member->id,
                    'sponsor_id' => $sponsor->id,
                    'email' => $invite['email'],
                    'phone' => '+1 (555) 010-22'.($index + 1),
                    'invite_link' => url('/register?ref=MEMBER-DEMO'),
                    'status' => $invite['status'],
                    'sent_at' => now()->subDays(12 - $index),
                    'accepted_at' => $invite['accepted_at'],
                    'expires_at' => now()->addDays(14 - $index),
                    'last_viewed_at' => now()->subDays($index + 1),
                    'metadata' => ['source' => 'dashboard-demo'],
                ]
            );
        }

        $events = [
            [
                'title' => 'Wednesday Market Structure Lab',
                'slug' => 'wednesday-market-structure-lab',
                'summary' => 'Live session covering market structure review, AI-assisted workflow checks, and risk-first planning.',
                'starts_at' => now()->addDays(2)->setTime(19, 0),
                'location' => 'Zoom',
                'audience' => 'Gold Members',
            ],
            [
                'title' => 'Sponsor Onboarding Roundtable',
                'slug' => 'sponsor-onboarding-roundtable',
                'summary' => 'Weekly onboarding checkpoint for members with recent invites and new direct-line activations.',
                'starts_at' => now()->addDays(5)->setTime(18, 30),
                'location' => 'Teams',
                'audience' => 'All Members',
            ],
        ];

        foreach ($events as $eventData) {
            $event = MemberEvent::updateOrCreate(
                ['slug' => $eventData['slug']],
                [
                    'title' => $eventData['title'],
                    'summary' => $eventData['summary'],
                    'starts_at' => $eventData['starts_at'],
                    'location' => $eventData['location'],
                    'audience' => $eventData['audience'],
                    'cta_label' => 'Reserve seat',
                    'is_published' => true,
                    'metadata' => ['source' => 'dashboard-demo'],
                ]
            );

            if ($event->slug === 'wednesday-market-structure-lab') {
                MemberEventRegistration::updateOrCreate(
                    ['member_event_id' => $event->id, 'user_id' => $member->id],
                    [
                        'status' => 'reserved',
                        'reserved_at' => now()->subHours(12),
                        'metadata' => ['source' => 'dashboard-demo'],
                    ]
                );
            }
        }

        $resources = [
            [
                'title' => 'New Member Onboarding Checklist',
                'slug' => 'new-member-onboarding-checklist',
                'summary' => 'Use this checklist to standardize setup, profile completion, and first-week training progress.',
                'category' => 'onboarding',
                'audience' => 'New Members',
                'format' => 'pdf',
                'resource_url' => url('/junk.html'),
            ],
            [
                'title' => 'Compliance Talking Points',
                'slug' => 'compliance-talking-points',
                'summary' => 'Approved sponsor-safe language for invite outreach, follow-ups, and webinar reminders.',
                'category' => 'compliance',
                'audience' => 'Sponsors',
                'format' => 'doc',
                'resource_url' => url('/junk.html'),
            ],
            [
                'title' => 'Risk Management Quick Guide',
                'slug' => 'risk-management-quick-guide',
                'summary' => 'Concise reminders for position sizing, scenario planning, and execution review.',
                'category' => 'education',
                'audience' => 'All Members',
                'format' => 'link',
                'resource_url' => url('/junk.html'),
            ],
        ];

        foreach ($resources as $resourceData) {
            $resource = MemberResource::updateOrCreate(
                ['slug' => $resourceData['slug']],
                [
                    'title' => $resourceData['title'],
                    'summary' => $resourceData['summary'],
                    'category' => $resourceData['category'],
                    'audience' => $resourceData['audience'],
                    'format' => $resourceData['format'],
                    'resource_url' => $resourceData['resource_url'],
                    'is_published' => true,
                    'metadata' => ['source' => 'dashboard-demo'],
                ]
            );

            if ($resource->slug === 'new-member-onboarding-checklist') {
                MemberResourceInteraction::updateOrCreate(
                    ['member_resource_id' => $resource->id, 'user_id' => $member->id],
                    [
                        'status' => 'reviewed',
                        'last_viewed_at' => now()->subDay(),
                        'view_count' => 2,
                        'metadata' => ['source' => 'dashboard-demo'],
                    ]
                );
            }
        }

        DB::table('notifications')->upsert([
            [
                'id' => (string) Str::uuid(),
                'type' => 'member.joined',
                'notifiable_type' => User::class,
                'notifiable_id' => $member->id,
                'data' => json_encode(['type' => 'member', 'title' => 'New member joined', 'message' => 'Alex Joined completed registration through your link.']),
                'read_at' => null,
                'created_at' => now()->subMinutes(18),
                'updated_at' => now()->subMinutes(18),
            ],
            [
                'id' => (string) Str::uuid(),
                'type' => 'training.reminder',
                'notifiable_type' => User::class,
                'notifiable_id' => $member->id,
                'data' => json_encode(['type' => 'training', 'title' => 'Training reminder', 'message' => 'Risk management training is due before the next webinar.']),
                'read_at' => null,
                'created_at' => now()->subHours(4),
                'updated_at' => now()->subHours(4),
            ],
            [
                'id' => (string) Str::uuid(),
                'type' => 'security.alert',
                'notifiable_type' => User::class,
                'notifiable_id' => $member->id,
                'data' => json_encode(['type' => 'security', 'title' => 'Security alert', 'message' => 'A new device signed into your dashboard.']),
                'read_at' => now()->subDay(),
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
        ], ['id'], ['data', 'read_at', 'updated_at']);

        foreach ([
            ['type' => 'member', 'title' => 'New member activated', 'description' => 'Alex Joined completed onboarding and entered your direct line.', 'created_at' => now()->subMinutes(18)],
            ['type' => 'presentation', 'title' => 'Presentation replay viewed', 'description' => 'Two invites watched more than 70% of the sponsor introduction presentation.', 'created_at' => now()->subHours(4)],
            ['type' => 'event', 'title' => 'Webinar reserved', 'description' => 'Wednesday Market Structure Lab was added to your event schedule.', 'created_at' => now()->subDay()],
            ['type' => 'resource', 'title' => 'Resource reviewed', 'description' => 'New Member Onboarding Checklist was reviewed and tracked.', 'created_at' => now()->subHours(10)],
        ] as $activity) {
            Activity::updateOrCreate(
                ['user_id' => $member->id, 'title' => $activity['title']],
                [
                    'type' => $activity['type'],
                    'description' => $activity['description'],
                    'created_at' => $activity['created_at'],
                    'updated_at' => $activity['created_at'],
                    'metadata' => ['source' => 'dashboard-demo'],
                    'ip_address' => '127.0.0.1',
                ]
            );
        }

        AuditLog::updateOrCreate(
            ['actor_id' => $sponsor->id, 'event' => 'member.dashboard.seeded'],
            [
                'auditable_type' => User::class,
                'auditable_id' => $member->id,
                'old_values' => ['status' => 'inactive'],
                'new_values' => ['status' => 'active'],
                'metadata' => ['source' => 'dashboard-demo', 'note' => 'Demo member activated for sponsor workspace preview'],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder',
            ]
        );
    }
}