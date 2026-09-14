<?php

namespace App\Livewire\Dashboard;

use App\Models\Activity;
use App\Models\Invitation;
use App\Models\SponsorRelationship;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Livewire\Component;

class DashboardHome extends Component
{
    public array $stats = [];
    public array $sponsor = [];
    public array $membership = [];
    public array $invite = [];
    public array $training = [];
    public array $event = [];
    public array $notificationsPreview = [];
    public array $activities = [];
    public array $quickActions = [];
    public array $recentMembers = [];

    public function mount(): void
    {
        $authUser = Auth::user();
        $user = $authUser instanceof User ? $authUser : null;
        $profile = $user?->profile;
        $directMembers = $user?->directMembers()->latest()->take(4)->get() ?? collect();
        $inviteCode = $profile?->invite_code ?: Str::upper(($user?->username ?? 'member').'-WLA');
        $trainingCompletion = (int) ($profile?->completion_percentage ?? 68);
        $invitationSummary = $this->loadInvitationSummary($user);
        $directMembersCount = $user ? $user->directMembers()->count() : 0;
        $activeMembersCount = $user ? $user->directMembers()->where('status', 'active')->count() : 0;
        $eventSummary = $this->loadEventSummary($user);
        $engagedInvites = $invitationSummary['engaged'];
        $roleName = $user && method_exists($user, 'getRoleNames') ? ($user->getRoleNames()->first() ?? 'member') : 'member';

        $this->stats = [
            ['title' => 'Direct Sponsored Members', 'value' => (string) $directMembersCount, 'change' => '+8% this month', 'icon' => 'users', 'accent' => 'blue'],
            ['title' => 'Total Downline', 'value' => (string) $this->countDownline($user), 'change' => 'Across all visible levels', 'icon' => 'network', 'accent' => 'gold'],
            ['title' => 'Active Members', 'value' => (string) $activeMembersCount, 'change' => 'Sponsor-qualified members', 'icon' => 'check-circle', 'accent' => 'emerald'],
            ['title' => 'Pending Invitations', 'value' => (string) $invitationSummary['pending'], 'change' => 'Needs follow-up this week', 'icon' => 'link', 'accent' => 'slate'],
            ['title' => 'Training Completion', 'value' => $trainingCompletion.'%', 'change' => 'Core path completion', 'icon' => 'academic-cap', 'accent' => 'blue'],
            ['title' => 'Invite Engagements', 'value' => (string) $engagedInvites, 'change' => 'Viewed or revisited invitations', 'icon' => 'presentation-chart', 'accent' => 'gold'],
            ['title' => 'Event Reminders', 'value' => (string) $eventSummary['count'], 'change' => $eventSummary['change'], 'icon' => 'calendar', 'accent' => 'emerald'],
        ];

        $relationship = $this->loadCurrentRelationship($user);
        $sponsor = $relationship?->sponsor ?? $user?->sponsor;
        $this->sponsor = [
            'name' => $sponsor?->username ?? 'Strategic Sponsor Desk',
            'email' => $sponsor?->email ?? 'sponsor@wealthlegacyalliance.test',
            'phone' => $sponsor?->profile?->phone_number ?? '+1 (555) 014-9210',
            'code' => $relationship?->sponsor_code ?? 'SP-'.str_pad((string) ($sponsor?->id ?? 14), 4, '0', STR_PAD_LEFT),
            'joined_date' => optional($relationship?->linked_at ?? $user?->created_at)->format('M d, Y') ?? now()->subMonths(4)->format('M d, Y'),
            'upline_label' => 'View sponsor path',
            'avatar' => $sponsor?->avatar_path ?? $sponsor?->profile?->avatar,
            'status' => $relationship ? 'Current Sponsor' : 'Active Sponsor',
        ];

        $this->membership = [
            'status' => Str::headline((string) ($user?->status?->value ?? $user?->status ?? 'active')),
            'role' => $roleName,
            'completion' => $trainingCompletion,
            'joined' => optional($profile?->membership_started_at ?? $user?->created_at)->format('F d, Y') ?? now()->subMonths(5)->format('F d, Y'),
            'tier' => $user?->rank_name ?? 'Gold Member',
        ];

        $this->invite = [
            'link' => url('/register?ref='.$inviteCode),
            'short_code' => $inviteCode,
            'accepted' => $invitationSummary['accepted'],
            'pending' => $invitationSummary['pending'],
            'conversion_rate' => $invitationSummary['conversion_rate'],
            'share_links' => [
                ['label' => 'Email', 'href' => 'mailto:?subject=Join%20Wealth%20Legacy%20Alliance&body='.urlencode(url('/register?ref='.$inviteCode))],
                ['label' => 'WhatsApp', 'href' => 'https://wa.me/?text='.urlencode(url('/register?ref='.$inviteCode))],
                ['label' => 'Telegram', 'href' => 'https://t.me/share/url?url='.urlencode(url('/register?ref='.$inviteCode))],
            ],
        ];

        $this->training = [
            'completion' => $trainingCompletion,
            'modules' => [
                ['title' => 'Getting Started Checklist', 'status' => 'Completed'],
                ['title' => 'Watch Presentation', 'status' => 'In progress'],
                ['title' => 'Sponsor Playbook Basics', 'status' => 'Completed'],
                ['title' => 'Member Onboarding Overview', 'status' => 'In progress'],
                ['title' => 'Compliance Basics', 'status' => 'Next up'],
                ['title' => 'Compliance Reminders', 'status' => 'Required'],
            ],
        ];

        $this->event = $eventSummary['feature'];

        $this->notificationsPreview = $this->loadNotificationsPreview();
        $this->activities = $this->loadActivities();
        $this->quickActions = [
            ['label' => 'Open Invite Center', 'route' => 'member.invite-link', 'icon' => 'link'],
            ['label' => 'Review Sponsor Path', 'route' => 'member.genealogy', 'icon' => 'network'],
            ['label' => 'Browse Resources', 'route' => 'member.resources', 'icon' => 'folder'],
            ['label' => 'Adjust Notification Settings', 'route' => 'member.settings.notifications', 'icon' => 'bell'],
        ];

        $this->recentMembers = $directMembers->map(fn (User $member) => [
            'name' => $member->username,
            'email' => $member->email,
            'joined' => optional($member->created_at)->diffForHumans() ?? 'Recently joined',
            'status' => Str::headline((string) ($member->status?->value ?? $member->status ?? 'active')),
        ])->values()->all();
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-home');
    }

    protected function countDownline(?User $user): int
    {
        return $user?->downlineCount() ?? 0;
    }

    protected function loadNotificationsPreview(): array
    {
        $authUser = Auth::user();
        $user = $authUser instanceof User ? $authUser : null;

        if ($user && $this->tableExists('notifications')) {
            return $user->notifications()->latest()->take(4)->get()->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => data_get($notification->data, 'title', 'Member update'),
                    'message' => data_get($notification->data, 'message', 'A new platform notification is available.'),
                    'time' => optional($notification->created_at)->diffForHumans() ?? 'Just now',
                    'type' => data_get($notification->data, 'type', 'system'),
                ];
            })->all();
        }

        return [
            ['id' => 'preview-1', 'title' => 'New member joined', 'message' => 'A direct invite completed registration through your sponsor link.', 'time' => '12 minutes ago', 'type' => 'member'],
            ['id' => 'preview-2', 'title' => 'Training reminder', 'message' => 'Risk management training is due before your next advanced webinar.', 'time' => '2 hours ago', 'type' => 'training'],
            ['id' => 'preview-3', 'title' => 'System announcement', 'message' => 'Member education portal maintenance is scheduled for Saturday.', 'time' => 'Yesterday', 'type' => 'system'],
        ];
    }

    protected function loadActivities(): array
    {
        $authUser = Auth::user();
        $user = $authUser instanceof User ? $authUser : null;

        if ($user && $this->tableExists('activities')) {
            return $user->activities()
                ->latest('created_at')
                ->take(5)
                ->get()
                ->map(fn (Activity $activity) => [
                    'title' => $activity->title,
                    'description' => $activity->description,
                    'time' => optional($activity->created_at)->diffForHumans() ?? 'Recently',
                    'type' => $activity->type,
                ])
                ->all();
        }

        return [
            ['title' => 'New member activated', 'description' => 'Janelle Morris completed onboarding and entered your direct line.', 'time' => '18 minutes ago', 'type' => 'member'],
            ['title' => 'Presentation replay viewed', 'description' => 'Three invites watched more than 70% of the premium overview presentation.', 'time' => '4 hours ago', 'type' => 'presentation'],
            ['title' => 'Compliance reminder issued', 'description' => 'Updated talking-point guidance is now available in Resources.', 'time' => 'Yesterday', 'type' => 'compliance'],
            ['title' => 'Webinar reserved', 'description' => 'You reserved the next AI-assisted market structure session.', 'time' => 'Yesterday', 'type' => 'event'],
        ];
    }

    protected function tableExists(string $table): bool
    {
        return Schema::hasTable($table);
    }

    protected function loadInvitationSummary(?User $user): array
    {
        if (! $user || ! $this->tableExists('invitations')) {
            return [
                'pending' => 0,
                'accepted' => 0,
                'engaged' => 0,
                'conversion_rate' => '0%',
            ];
        }

        $invitations = Invitation::query()
            ->where('inviter_id', $user->id)
            ->get();

        $total = $invitations->count();
        $pending = $invitations->whereIn('status', ['pending', 'sent'])->count();
        $accepted = $invitations->where('status', 'accepted')->count();
        $engaged = $invitations->whereNotNull('last_viewed_at')->count();
        $conversionRate = $total > 0 ? (int) round(($accepted / $total) * 100) : 0;

        return [
            'pending' => $pending,
            'accepted' => $accepted,
            'engaged' => $engaged,
            'conversion_rate' => $conversionRate.'%',
        ];
    }

    protected function loadCurrentRelationship(?User $user): ?SponsorRelationship
    {
        if (! $user || ! $this->tableExists('sponsor_relationships')) {
            return null;
        }

        return $user->sponsorRelationships()
            ->where('is_current', true)
            ->with('sponsor.profile')
            ->latest('linked_at')
            ->first();
    }

    protected function loadEventSummary(?User $user): array
    {
        if (! $user || ! $this->tableExists('activities')) {
            return [
                'count' => 0,
                'change' => 'No event reminders yet',
                'feature' => [
                    'title' => 'No event scheduled yet',
                    'datetime' => 'Stay tuned',
                    'description' => 'Your next webinar reservation will appear here once event activity is recorded.',
                    'cta_label' => 'Open events',
                ],
            ];
        }

        $eventActivities = $user->activities()
            ->where('type', 'event')
            ->latest('created_at')
            ->get();

        $latestEvent = $eventActivities->first();
        $latestCreatedAt = $latestEvent?->created_at;

        return [
            'count' => $eventActivities->count(),
            'change' => $latestCreatedAt instanceof CarbonInterface
                ? 'Latest event action '.$latestCreatedAt->diffForHumans()
                : 'Event activity will appear here',
            'feature' => [
                'title' => $latestEvent?->title ?? 'No event scheduled yet',
                'datetime' => $latestCreatedAt instanceof CarbonInterface
                    ? $latestCreatedAt->format('D, M d \\a\\t g:i A')
                    : 'Stay tuned',
                'description' => $latestEvent?->description ?? 'Your next webinar reservation will appear here once event activity is recorded.',
                'cta_label' => $latestEvent ? 'Review event activity' : 'Open events',
            ],
        ];
    }
}