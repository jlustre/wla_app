<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MemberContentController extends Controller
{
    public function page(Request $request, string $page): View
    {
        $pages = [
            'sponsor' => [
                'key' => 'sponsor',
                'title' => 'My Sponsor',
                'eyebrow' => 'Sponsor Relationship',
                'description' => 'Review sponsor details, preferred contact channels, and your upline connection history.',
            ],
            'invite-link' => [
                'key' => 'invite-link',
                'title' => 'My Invite Link',
                'eyebrow' => 'Member Growth',
                'description' => 'Manage your personal invitation assets, recent conversions, and sponsor attribution.',
            ],
            'sponsored-members' => [
                'key' => 'sponsored-members',
                'title' => 'My Sponsored Members',
                'eyebrow' => 'Downline Management',
                'description' => 'Monitor direct sponsored members, activation state, and onboarding readiness.',
            ],
            'presentations' => [
                'key' => 'presentations',
                'title' => 'Presentations',
                'eyebrow' => 'Sales Enablement',
                'description' => 'Curate sponsor-safe presentations, decks, and replay assets for member sharing.',
            ],
            'watch-video' => [
                'key' => 'watch-video',
                'title' => 'Watch Video',
                'eyebrow' => 'Media Library',
                'description' => 'Centralize the latest compliance-approved introductions, tutorials, and event replays.',
            ],
            'resources' => [
                'key' => 'resources',
                'title' => 'Resources',
                'eyebrow' => 'Knowledge Hub',
                'description' => 'Gather compliant documents, onboarding kits, and sponsor-ready talking points.',
            ],
            'events' => [
                'key' => 'events',
                'title' => 'Events / Webinars',
                'eyebrow' => 'Calendar',
                'description' => 'Coordinate upcoming live sessions, replays, reminders, and attendance prompts.',
            ],
            'notifications' => [
                'key' => 'notifications',
                'title' => 'Notifications',
                'eyebrow' => 'Inbox',
                'description' => 'Review operational alerts, member updates, webinar reminders, and security notices.',
            ],
            'support' => [
                'key' => 'support',
                'title' => 'Support',
                'eyebrow' => 'Member Help',
                'description' => 'Give members a clear path to contact sponsor, support, and security escalation teams.',
            ],
            'settings' => [
                'key' => 'settings',
                'title' => 'Settings',
                'eyebrow' => 'Account Control',
                'description' => 'Manage profile, security, notifications, privacy, and theme preferences.',
            ],
        ];

        $selectedPage = Arr::get($pages, $page);

        if (! $selectedPage) {
            throw new NotFoundHttpException();
        }

        return view('member.content-page', [
            'page' => $selectedPage,
        ]);
    }

    public function setting(Request $request, string $setting): View
    {
        $settings = [
            'profile' => ['key' => 'profile', 'title' => 'Profile Settings', 'description' => 'Edit your personal details, sponsor-facing profile, and onboarding status.'],
            'security' => ['key' => 'security', 'title' => 'Account Security', 'description' => 'Review verification, sessions, and account-protection recommendations.'],
            'password' => ['key' => 'password', 'title' => 'Password Change', 'description' => 'Rotate your password and keep recovery options current.'],
            'notifications' => ['key' => 'notifications', 'title' => 'Notification Preferences', 'description' => 'Choose which alerts arrive in-app, by email, or both.'],
            'privacy' => ['key' => 'privacy', 'title' => 'Privacy Settings', 'description' => 'Control visibility for profile fields, sponsor contact, and communication preferences.'],
            'theme' => ['key' => 'theme', 'title' => 'Theme Preference', 'description' => 'Use a light or dark dashboard theme and keep your preference synced.'],
        ];

        $selectedSetting = Arr::get($settings, $setting);

        if (! $selectedSetting) {
            throw new NotFoundHttpException();
        }

        return view('member.settings.page', [
            'setting' => $selectedSetting,
        ]);
    }
}