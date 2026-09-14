<?php

namespace App\Support;

class MemberSidebarMenu
{
    /**
     * @return array<int, array{label: string, items: array<int, array<string, mixed>>}>
     */
    public static function groups(): array
    {
        return [
            [
                'label' => 'Workspace',
                'items' => [
                    ['label' => 'Dashboard', 'route' => 'member.dashboard', 'icon' => 'home', 'title' => 'Dashboard'],
                    ['label' => 'My Profile', 'route' => 'profile', 'icon' => 'user', 'title' => 'My Profile'],
                    ['label' => 'My Sponsor', 'route' => 'member.sponsor', 'icon' => 'shield', 'title' => 'My Sponsor'],
                    ['label' => 'My Invite Link', 'route' => 'member.invite-link', 'icon' => 'link', 'title' => 'My Invite Link'],
                    ['label' => 'My Sponsored Members', 'route' => 'member.sponsored-members', 'icon' => 'users', 'title' => 'My Sponsored Members'],
                    ['label' => 'Sponsorship Tree', 'route' => 'member.genealogy', 'icon' => 'network', 'title' => 'Sponsorship Tree', 'spa' => false],
                ],
            ],
            [
                'label' => 'Education',
                'items' => [
                    ['label' => 'Presentations', 'route' => 'member.presentations', 'icon' => 'presentation-chart', 'title' => 'Presentations'],
                    ['label' => 'Watch Video', 'route' => 'member.watch-video', 'icon' => 'play', 'title' => 'Watch Video'],
                    ['label' => 'Resources', 'route' => 'member.resources', 'icon' => 'folder', 'title' => 'Resources'],
                    ['label' => 'Events / Webinars', 'route' => 'member.events', 'icon' => 'calendar', 'title' => 'Events / Webinars'],
                ],
            ],
            [
                'label' => 'Account',
                'items' => [
                    ['label' => 'Notifications', 'route' => 'member.notifications', 'icon' => 'bell', 'title' => 'Notifications'],
                    ['label' => 'Support', 'route' => 'member.support', 'icon' => 'life-buoy', 'title' => 'Support'],
                    [
                        'label' => 'Settings',
                        'route' => 'member.settings',
                        'icon' => 'cog',
                        'title' => 'Settings',
                        'children' => [
                            ['label' => 'Profile Settings', 'route' => 'member.settings.profile', 'title' => 'Profile Settings'],
                            ['label' => 'Account Security', 'route' => 'member.settings.security', 'title' => 'Account Security'],
                            ['label' => 'Password Change', 'route' => 'member.settings.password', 'title' => 'Password Change'],
                            ['label' => 'Notification Preferences', 'route' => 'member.settings.notifications', 'title' => 'Notification Preferences'],
                            ['label' => 'Privacy Settings', 'route' => 'member.settings.privacy', 'title' => 'Privacy Settings'],
                            ['label' => 'Theme Preference', 'route' => 'member.settings.theme', 'title' => 'Theme Preference'],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<int, array{label: string, route: string, icon: string, title: string}>
     */
    public static function adminItems(): array
    {
        return [
            ['label' => 'Admin Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home', 'title' => 'Admin Dashboard'],
            ['label' => 'User Management', 'route' => 'admin.users.index', 'icon' => 'users', 'title' => 'User Management'],
            ['label' => 'Sponsor Relationships', 'route' => 'admin.sponsor-relationships.index', 'icon' => 'network', 'title' => 'Sponsor Relationships'],
            ['label' => 'Audit Logs', 'route' => 'admin.audit-logs.index', 'icon' => 'clipboard-document-list', 'title' => 'Audit Logs'],
            ['label' => 'Platform Settings', 'route' => 'admin.settings', 'icon' => 'cog', 'title' => 'Platform Settings'],
        ];
    }

    /**
     * @return array<int, array{label: string, route: string, title: string}>
     */
    public static function memberDestinations(): array
    {
        $destinations = [];

        foreach (self::groups() as $group) {
            foreach ($group['items'] as $item) {
                $destinations[] = [
                    'label' => $item['label'],
                    'route' => $item['route'],
                    'title' => $item['title'],
                ];

                foreach ($item['children'] ?? [] as $child) {
                    $destinations[] = [
                        'label' => $child['label'],
                        'route' => $child['route'],
                        'title' => $child['title'],
                    ];
                }
            }
        }

        return $destinations;
    }
}
