<?php

namespace App\Support;

use App\Models\CalendarEvent;
use App\Models\Invitation;
use App\Models\MemberCompanyAffiliation;
use App\Models\MemberResourceInteraction;
use App\Models\Prospect;
use App\Models\User;

/**
 * The fixed catalog of new-member onboarding checklist steps, each paired
 * with a closure that auto-detects completion from data the member has
 * already created elsewhere in the app — nothing here needs to be manually
 * ticked. Consumed by App\Services\OnboardingService.
 */
class OnboardingSteps
{
    /**
     * @return array<int, array{key: string, title: string, description: string, icon: string, route: string, check: callable(User): bool}>
     */
    public static function all(): array
    {
        return [
            [
                'key' => 'complete_profile',
                'title' => 'Complete Your Profile',
                'description' => 'Add a bio, photo, and contact details so your sponsor and team can get to know you.',
                'icon' => 'user',
                'route' => 'member.settings.profile',
                'check' => function (User $user): bool {
                    $profile = $user->profile;

                    if (! $profile) {
                        return false;
                    }

                    if ((int) ($profile->completion_percentage ?? 0) >= 80) {
                        return true;
                    }

                    return filled($profile->bio) && filled($profile->avatar);
                },
            ],
            [
                'key' => 'add_prospect',
                'title' => 'Add Your First Prospect',
                'description' => "Start building your pipeline — add someone you'd like to invite.",
                'icon' => 'user-plus',
                'route' => 'prospects.index',
                'check' => fn (User $user): bool => Prospect::where('user_id', $user->id)->exists(),
            ],
            [
                'key' => 'send_invite',
                'title' => 'Send an Invitation',
                'description' => 'Share your personal invite link or send a tracked invitation to a prospect.',
                'icon' => 'link',
                'route' => 'member.invite-link',
                'check' => fn (User $user): bool => Invitation::where('inviter_id', $user->id)->exists(),
            ],
            [
                'key' => 'join_company',
                'title' => 'Explore an Affiliated Company',
                'description' => 'Browse the companies available to you and get positioned early.',
                'icon' => 'building-office',
                'route' => 'member.dashboard',
                'check' => fn (User $user): bool => MemberCompanyAffiliation::where('user_id', $user->id)->exists(),
            ],
            [
                'key' => 'setup_calendar',
                'title' => 'Set Up Your Calendar',
                'description' => 'Add your first appointment, follow-up, or reminder to your personal calendar.',
                'icon' => 'calendar',
                'route' => 'member.calendar',
                'check' => fn (User $user): bool => CalendarEvent::where('user_id', $user->id)
                    ->where(function ($q) {
                        $q->whereNull('description')->orWhere('description', 'not like', '%[onboarding-auto]%');
                    })
                    ->exists(),
            ],
            [
                'key' => 'explore_resources',
                'title' => 'Explore Training Resources',
                'description' => 'Check out the resource library to sharpen your skills before your first conversation.',
                'icon' => 'folder',
                'route' => 'member.resources',
                'check' => fn (User $user): bool => MemberResourceInteraction::where('user_id', $user->id)->exists(),
            ],
        ];
    }

    public static function find(string $key): ?array
    {
        foreach (self::all() as $step) {
            if ($step['key'] === $key) {
                return $step;
            }
        }

        return null;
    }

    public static function keys(): array
    {
        return array_column(self::all(), 'key');
    }
}
