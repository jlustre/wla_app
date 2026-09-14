<?php

namespace App\Support;

class AdminSidebarMenu
{
    /**
     * @return array<int, array{label: string, items: array<int, array<string, mixed>>}>
     */
    public static function groups(): array
    {
        return [
            [
                'label' => 'Overview',
                'items' => [
                    ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home', 'title' => 'Admin Dashboard'],
                    [
                        'label' => 'Downline Tree',
                        'route' => 'admin.downline',
                        'icon' => 'network',
                        'title' => 'Downline Tree',
                        'children' => [
                            ['label' => 'Tree Viewer', 'route' => 'admin.downline', 'title' => 'Downline Tree'],
                            ['label' => 'Placement Rules', 'route' => 'admin.downline.placement-rules', 'title' => 'Placement Rules'],
                            ['label' => 'Spillover Logic', 'route' => 'admin.downline.spillover', 'title' => 'Spillover Logic'],
                        ],
                    ],
                ],
            ],
            [
                'label' => 'CRM',
                'items' => [
                    ['label' => 'Prospects', 'route' => 'admin.prospects.index', 'icon' => 'users', 'title' => 'Prospects'],
                    ['label' => 'Leads Pipeline', 'route' => 'admin.pipeline', 'icon' => 'presentation-chart', 'title' => 'Leads Pipeline'],
                    ['label' => 'Follow-ups', 'route' => 'admin.follow-ups', 'icon' => 'calendar', 'title' => 'Follow-ups'],
                    ['label' => 'Activity Logs', 'route' => 'admin.activity-logs', 'icon' => 'clock', 'title' => 'Activity Logs'],
                    ['label' => 'Notifications', 'route' => 'admin.notifications', 'icon' => 'bell', 'title' => 'Notifications'],
                ],
            ],
            [
                'label' => 'Administration',
                'items' => [
                    ['label' => 'Members Management', 'route' => 'admin.users.index', 'icon' => 'users', 'title' => 'User Management'],
                    ['label' => 'Role Management', 'route' => 'admin.roles.index', 'icon' => 'shield', 'title' => 'Role Management'],
                    ['label' => 'Company Management', 'route' => 'admin.companies.index', 'icon' => 'folder', 'title' => 'Manage Companies'],
                    ['label' => 'Sponsor Relationships', 'route' => 'admin.sponsor-relationships.index', 'icon' => 'network', 'title' => 'Sponsor Relationships'],
                    ['label' => 'Settings', 'route' => 'admin.settings', 'icon' => 'cog', 'title' => 'Platform Settings'],
                    ['label' => 'Audit Logs', 'route' => 'admin.audit-logs.index', 'icon' => 'clipboard-document-list', 'title' => 'Audit Logs'],
                    ['label' => 'Reports and Analytics', 'route' => 'admin.reports', 'icon' => 'presentation-chart', 'title' => 'Reports and Analytics'],
                    ['label' => 'AI Insights', 'route' => 'admin.ai-insights', 'icon' => 'chip', 'title' => 'AI Insights'],
                ],
            ],
        ];
    }

    /**
     * @return array<int, array{label: string, route: string, title: string}>
     */
    public static function destinations(): array
    {
        $destinations = [];

        foreach (self::groups() as $group) {
            foreach ($group['items'] as $item) {
                $destinations[$item['route']] = [
                    'label' => $item['label'],
                    'route' => $item['route'],
                    'title' => $item['title'],
                ];

                foreach ($item['children'] ?? [] as $child) {
                    $destinations[$child['route']] = [
                        'label' => $child['label'],
                        'route' => $child['route'],
                        'title' => $child['title'],
                    ];
                }
            }
        }

        return array_values($destinations);
    }
}
