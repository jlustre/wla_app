<?php

namespace App\Livewire\Member;

use App\Models\Activity;
use App\Models\Invitation;
use App\Models\MemberEvent;
use App\Models\MemberEventRegistration;
use App\Models\MemberResource;
use App\Models\MemberResourceInteraction;
use App\Models\SponsorRelationship;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Livewire\Component;

class ContentHub extends Component
{
    public string $page = 'sponsor';

    public string $title = '';

    public string $description = '';

    public array $metrics = [];

    public array $primaryCards = [];

    public array $secondaryCards = [];

    public array $table = [];

    public array $timeline = [];

    public array $inviteActions = [];

    public array $notificationActions = [];

    public array $eventActions = [];

    public array $resourceActions = [];

    public string $inviteLink = '';

    public ?string $statusMessage = null;

    public function mount(string $page, string $title = '', string $description = ''): void
    {
        $this->page = $page;
        $this->title = $title;
        $this->description = $description;

        $this->loadPageData();
    }

    public function copyInviteLink(): void
    {
        if ($this->inviteLink === '') {
            return;
        }

        $this->dispatch('copy-to-clipboard', value: $this->inviteLink);
        $this->statusMessage = 'Invite link copied to the clipboard.';
    }

    public function resendInvite(int $inviteId): void
    {
        $user = $this->resolveUser();

        if (! Schema::hasTable('invitations')) {
            return;
        }

        $invite = Invitation::query()
            ->where('inviter_id', $user->id)
            ->findOrFail($inviteId);

        if ($invite->status !== 'accepted') {
            $invite->update([
                'status' => 'sent',
                'sent_at' => now(),
                'expires_at' => now()->addDays(14),
            ]);
        }

        $this->recordActivity($user, 'invite', 'Invite resent', 'Invite follow-up sent to '.($invite->email ?? $invite->phone ?? 'lead').'.');
        $this->statusMessage = 'Invite resent and follow-up activity recorded.';

        $this->loadPageData();
    }

    public function markNotificationAsRead(string $notificationId): void
    {
        $user = $this->resolveUser();

        if (! Schema::hasTable('notifications')) {
            return;
        }

        $notification = $user->notifications()->whereKey($notificationId)->first();

        if ($notification && ! $notification->read_at) {
            $notification->markAsRead();
            $this->statusMessage = 'Notification marked as read.';
        }

        $this->loadPageData();
    }

    public function reserveEvent(int $eventId): void
    {
        $user = $this->resolveUser();

        if (! Schema::hasTable('member_events') || ! Schema::hasTable('member_event_registrations')) {
            return;
        }

        $event = MemberEvent::query()
            ->where('is_published', true)
            ->findOrFail($eventId);

        MemberEventRegistration::query()->updateOrCreate(
            [
                'member_event_id' => $event->id,
                'user_id' => $user->id,
            ],
            [
                'status' => 'reserved',
                'reserved_at' => now(),
            ],
        );

        $this->recordActivity($user, 'event', 'Event reserved', 'Reserved a seat for '.$event->title.'.', $event);
        $this->statusMessage = 'Event reservation saved.';

        $this->loadPageData();
    }

    public function markResourceReviewed(int $resourceId): void
    {
        $user = $this->resolveUser();

        if (! Schema::hasTable('member_resources') || ! Schema::hasTable('member_resource_interactions')) {
            return;
        }

        $resource = MemberResource::query()
            ->where('is_published', true)
            ->findOrFail($resourceId);

        $interaction = MemberResourceInteraction::query()->firstOrNew([
            'member_resource_id' => $resource->id,
            'user_id' => $user->id,
        ]);

        $interaction->status = 'reviewed';
        $interaction->last_viewed_at = now();
        $interaction->view_count = (int) $interaction->view_count + 1;
        $interaction->save();

        $this->recordActivity($user, 'resource', 'Resource reviewed', 'Reviewed '.$resource->title.'.', $resource);
        $this->statusMessage = 'Resource review saved.';

        $this->loadPageData();
    }

    public function render()
    {
        return view('livewire.member.content-hub');
    }

    protected function loadPageData(): void
    {
        $this->inviteActions = [];
        $this->notificationActions = [];
        $this->eventActions = [];
        $this->resourceActions = [];

        $user = $this->resolveUser();
        $profile = $user->profile;
        $inviteCode = $profile?->invite_code ?: Str::upper($user->username.'-WLA');
        $this->inviteLink = url('/register?ref='.$inviteCode);
        $invitations = $this->invitationsFor($user);
        $directMembers = $user->directMembers()->with('profile')->latest()->get();
        $notifications = $this->notificationsFor($user);
        $activities = $this->activitiesFor($user);
        $relationship = $this->currentRelationshipFor($user);
        $events = $this->eventsFor();
        $registrations = $this->eventRegistrationsFor($user);
        $resources = $this->resourcesFor();
        $resourceInteractions = $this->resourceInteractionsFor($user);
        $completion = (int) ($profile?->completion_percentage ?? 0);

        $summary = [
            'pendingInvites' => $invitations->whereIn('status', ['pending', 'sent'])->count(),
            'acceptedInvites' => $invitations->where('status', 'accepted')->count(),
            'engagedInvites' => $invitations->whereNotNull('last_viewed_at')->count(),
            'directMembers' => $directMembers->count(),
            'activeMembers' => $directMembers->filter(fn (User $member) => (string) ($member->status?->value ?? $member->status) === 'active')->count(),
            'notifications' => $notifications->count(),
            'unreadNotifications' => $notifications->whereNull('read_at')->count(),
            'activities' => $activities->count(),
            'eventActivities' => $activities->where('type', 'event')->count(),
            'presentationActivities' => $activities->where('type', 'presentation')->count(),
            'trainingCompletion' => $completion,
            'publishedEvents' => $events->count(),
            'reservedEvents' => $registrations->where('status', 'reserved')->count(),
            'publishedResources' => $resources->count(),
            'reviewedResources' => $resourceInteractions->where('status', 'reviewed')->count(),
        ];

        [$this->metrics, $this->primaryCards, $this->secondaryCards, $this->table, $this->timeline] = match ($this->page) {
            'sponsor' => $this->buildSponsorPage($user, $relationship, $summary, $activities),
            'invite-link' => $this->buildInvitePage($inviteCode, $summary, $invitations),
            'sponsored-members' => $this->buildMembersPage($summary, $directMembers),
            'presentations' => $this->buildPresentationsPage($summary, $activities, $invitations),
            'watch-video' => $this->buildVideoPage($completion, $activities),
            'resources' => $this->buildResourcesPage($summary, $resources, $resourceInteractions),
            'events' => $this->buildEventsPage($summary, $events, $registrations, $notifications),
            'notifications' => $this->buildNotificationsPage($summary, $notifications),
            'support' => $this->buildSupportPage($user, $relationship, $summary),
            'settings' => $this->buildSettingsOverviewPage($user, $summary),
            default => [[], [], [], [], []],
        };
    }

    protected function resolveUser(): User
    {
        $authUser = Auth::user();

        abort_unless($authUser instanceof User, 403);

        return $authUser;
    }

    protected function invitationsFor(User $user): Collection
    {
        if (! Schema::hasTable('invitations')) {
            return collect();
        }

        return Invitation::query()
            ->where('inviter_id', $user->id)
            ->latest('created_at')
            ->get();
    }

    protected function notificationsFor(User $user): Collection
    {
        if (! Schema::hasTable('notifications')) {
            return collect();
        }

        return $user->notifications()->latest()->get();
    }

    protected function activitiesFor(User $user): Collection
    {
        if (! Schema::hasTable('activities')) {
            return collect();
        }

        return $user->activities()->latest('created_at')->get();
    }

    protected function eventsFor(): Collection
    {
        if (! Schema::hasTable('member_events')) {
            return collect();
        }

        return MemberEvent::query()
            ->where('is_published', true)
            ->orderBy('starts_at', 'asc')
            ->get();
    }

    protected function eventRegistrationsFor(User $user): Collection
    {
        if (! Schema::hasTable('member_event_registrations')) {
            return collect();
        }

        return MemberEventRegistration::query()
            ->where('user_id', $user->id)
            ->get()
            ->keyBy('member_event_id');
    }

    protected function resourcesFor(): Collection
    {
        if (! Schema::hasTable('member_resources')) {
            return collect();
        }

        return MemberResource::query()
            ->where('is_published', true)
            ->orderBy('category', 'asc')
            ->orderBy('title', 'asc')
            ->get();
    }

    protected function resourceInteractionsFor(User $user): Collection
    {
        if (! Schema::hasTable('member_resource_interactions')) {
            return collect();
        }

        return MemberResourceInteraction::query()
            ->where('user_id', $user->id)
            ->get()
            ->keyBy('member_resource_id');
    }

    protected function currentRelationshipFor(User $user): ?SponsorRelationship
    {
        if (! Schema::hasTable('sponsor_relationships')) {
            return null;
        }

        return $user->sponsorRelationships()
            ->where('is_current', true)
            ->with('sponsor.profile')
            ->latest('linked_at')
            ->first();
    }

    protected function buildSponsorPage(User $user, ?SponsorRelationship $relationship, array $summary, Collection $activities): array
    {
        $sponsor = $relationship?->sponsor ?? $user->sponsor;
        $linkedAt = $relationship?->linked_at;

        return [
            [
                ['label' => 'Current Sponsor', 'value' => $sponsor?->username ?? 'Not assigned'],
                ['label' => 'Sponsor Code', 'value' => $relationship?->sponsor_code ?? 'Pending'],
                ['label' => 'Days Linked', 'value' => $linkedAt ? (string) $linkedAt->diffInDays(now()) : '0'],
            ],
            [
                ['title' => 'Primary sponsor channel', 'body' => $sponsor?->email ?? 'No sponsor email on file', 'meta' => $sponsor?->profile?->phone_number ?? 'Phone not shared yet'],
                ['title' => 'Escalation path', 'body' => 'Use genealogy and support routing to keep sponsor requests inside approved channels.', 'meta' => 'Compliance-safe member support'],
            ],
            [
                ['title' => 'Shared action queue', 'body' => 'Coordinate pending invites, member onboarding, and training completion with your sponsor.', 'meta' => $summary['pendingInvites'].' invites waiting on follow-up'],
            ],
            [
                'columns' => ['Checkpoint', 'Status', 'Timestamp'],
                'rows' => [
                    ['Sponsor linked', $relationship ? 'Complete' : 'Pending', $linkedAt?->format('M d, Y') ?? 'Not recorded'],
                    ['Direct members active', (string) $summary['activeMembers'], 'Live count'],
                    ['Recent sponsor-facing activity', (string) $activities->take(3)->count(), 'Last 30 days'],
                ],
            ],
            $activities->take(4)->map(fn ($activity) => [
                'title' => $activity->title,
                'body' => $activity->description,
                'time' => optional($activity->created_at)->diffForHumans() ?? 'Recently',
            ])->all(),
        ];
    }

    protected function buildInvitePage(string $inviteCode, array $summary, Collection $invitations): array
    {
        $this->inviteActions = $invitations->take(6)->map(fn (Invitation $invite) => [
            'id' => $invite->id,
            'label' => $invite->email ?? $invite->phone ?? 'Invite',
            'status' => Str::headline($invite->status),
            'sent' => optional($invite->sent_at ?? $invite->created_at)->diffForHumans() ?? 'Not sent',
            'can_resend' => $invite->status !== 'accepted',
        ])->all();

        $conversion = $summary['acceptedInvites'] + $summary['pendingInvites'] > 0
            ? (int) round(($summary['acceptedInvites'] / max($invitations->count(), 1)) * 100)
            : 0;

        return [
            [
                ['label' => 'Invite Code', 'value' => $inviteCode],
                ['label' => 'Pending', 'value' => (string) $summary['pendingInvites']],
                ['label' => 'Accepted', 'value' => (string) $summary['acceptedInvites']],
                ['label' => 'Conversion', 'value' => $conversion.'%'],
            ],
            [
                ['title' => 'Primary share link', 'body' => url('/register?ref='.$inviteCode), 'meta' => 'Use this for compliant email and messenger sharing'],
                ['title' => 'Engaged invites', 'body' => $summary['engagedInvites'].' leads have viewed an invite asset.', 'meta' => 'Tracked from last viewed timestamps'],
            ],
            [
                ['title' => 'Follow-up rule', 'body' => 'Prioritize pending or sent invites that were viewed but not yet accepted.', 'meta' => 'Best next action for sponsor growth'],
            ],
            [
                'columns' => ['Invitee', 'Status', 'Viewed', 'Sent'],
                'rows' => $invitations->take(6)->map(fn (Invitation $invite) => [
                    $invite->email ?? $invite->phone ?? 'Unknown',
                    Str::headline($invite->status),
                    optional($invite->last_viewed_at)->diffForHumans() ?? 'Not viewed',
                    optional($invite->sent_at ?? $invite->created_at)->format('M d, Y') ?? 'Not sent',
                ])->all(),
            ],
            $invitations->take(4)->map(fn (Invitation $invite) => [
                'title' => ($invite->email ?? $invite->phone ?? 'Invite').': '.Str::headline($invite->status),
                'body' => 'Sent '.(optional($invite->sent_at ?? $invite->created_at)->diffForHumans() ?? 'recently').'.',
                'time' => optional($invite->last_viewed_at)->diffForHumans() ?? 'Awaiting engagement',
            ])->all(),
        ];
    }

    protected function buildMembersPage(array $summary, Collection $directMembers): array
    {
        $averageCompletion = (int) round($directMembers->avg(fn (User $member) => (int) ($member->profile?->completion_percentage ?? 0)) ?? 0);

        return [
            [
                ['label' => 'Direct Members', 'value' => (string) $summary['directMembers']],
                ['label' => 'Active Members', 'value' => (string) $summary['activeMembers']],
                ['label' => 'Average Completion', 'value' => $averageCompletion.'%'],
            ],
            [
                ['title' => 'Onboarding queue', 'body' => $directMembers->where(fn (User $member) => (int) ($member->profile?->completion_percentage ?? 0) < 70)->count().' members need onboarding follow-up.', 'meta' => 'Members below 70% completion'],
                ['title' => 'Profile readiness', 'body' => $directMembers->where(fn (User $member) => blank($member->profile?->phone_number) || blank($member->profile?->city))->count().' members are missing key profile details.', 'meta' => 'Useful for sponsor outreach'],
            ],
            [
                ['title' => 'Support routing', 'body' => 'Use this list to route training, invite, and account questions without leaving the member area.', 'meta' => 'Keep support paths explicit'],
            ],
            [
                'columns' => ['Member', 'Status', 'Completion', 'Joined'],
                'rows' => $directMembers->take(8)->map(fn (User $member) => [
                    $member->username,
                    Str::headline((string) ($member->status?->value ?? $member->status ?? 'active')),
                    ((int) ($member->profile?->completion_percentage ?? 0)).'%',
                    optional($member->created_at)->format('M d, Y') ?? 'Recently',
                ])->all(),
            ],
            $directMembers->take(4)->map(fn (User $member) => [
                'title' => $member->username,
                'body' => $member->email,
                'time' => optional($member->created_at)->diffForHumans() ?? 'Recently joined',
            ])->all(),
        ];
    }

    protected function buildPresentationsPage(array $summary, Collection $activities, Collection $invitations): array
    {
        return [
            [
                ['label' => 'Presentation Replays', 'value' => (string) $summary['presentationActivities']],
                ['label' => 'Invite Engagements', 'value' => (string) $summary['engagedInvites']],
            ],
            [
                ['title' => 'Featured deck flow', 'body' => 'Start with sponsor introduction assets and only move to advanced education after engagement.', 'meta' => 'Approved sequencing'],
                ['title' => 'Replay signals', 'body' => $activities->where('type', 'presentation')->count().' presentation activities were recorded for this member.', 'meta' => 'Use this to shape follow-up'],
            ],
            [],
            [
                'columns' => ['Asset', 'Signal', 'Recommended next step'],
                'rows' => [
                    ['Sponsor introduction', (string) $summary['engagedInvites'].' viewers engaged', 'Send invite follow-up'],
                    ['Education overview', (string) $summary['presentationActivities'].' replay events', 'Route to training'],
                    ['Compliance deck', 'Always available', 'Share before outreach'],
                ],
            ],
            $invitations->whereNotNull('last_viewed_at')->take(4)->map(fn (Invitation $invite) => [
                'title' => $invite->email ?? 'Invite viewer',
                'body' => 'Viewed the linked presentation or invite asset.',
                'time' => optional($invite->last_viewed_at)->diffForHumans() ?? 'Recently',
            ])->values()->all(),
        ];
    }

    protected function buildVideoPage(int $completion, Collection $activities): array
    {
        return [
            [
                ['label' => 'Watch Progress', 'value' => $completion.'%'],
                ['label' => 'Replay Events', 'value' => (string) $activities->where('type', 'presentation')->count()],
            ],
            [
                ['title' => 'Recommended queue', 'body' => 'Continue from sponsor orientation into risk management and webinar replay content.', 'meta' => 'Keep the sequence intentional'],
                ['title' => 'Next best action', 'body' => $completion < 70 ? 'Finish the core training playlist before advanced topics.' : 'Continue into live lab replays and scenario review.', 'meta' => 'Based on current completion'],
            ],
            [],
            [
                'columns' => ['Video track', 'Status', 'Priority'],
                'rows' => [
                    ['Sponsor orientation', 'Available', 'High'],
                    ['Risk management basics', $completion >= 55 ? 'Completed' : 'In progress', 'High'],
                    ['Market structure replay', $activities->where('type', 'event')->count() > 0 ? 'Reserved' : 'Suggested', 'Medium'],
                ],
            ],
            $activities->take(4)->map(fn ($activity) => [
                'title' => $activity->title,
                'body' => $activity->description,
                'time' => optional($activity->created_at)->diffForHumans() ?? 'Recently',
            ])->all(),
        ];
    }

    protected function buildResourcesPage(array $summary, Collection $resources, Collection $resourceInteractions): array
    {
        $this->resourceActions = $resources->take(6)->map(function (MemberResource $resource) use ($resourceInteractions) {
            $interaction = $resourceInteractions->get($resource->id);

            return [
                'id' => $resource->id,
                'title' => $resource->title,
                'category' => $resource->category,
                'format' => $resource->format,
                'status' => $interaction?->status ?? 'new',
                'view_count' => (int) ($interaction?->view_count ?? 0),
                'url' => $resource->resource_url,
            ];
        })->all();

        return [
            [
                ['label' => 'Published Resources', 'value' => (string) $summary['publishedResources']],
                ['label' => 'Reviewed Resources', 'value' => (string) $summary['reviewedResources']],
            ],
            [
                ['title' => 'Member docs', 'body' => 'Resources are now loaded from a dedicated content table instead of static placeholders.', 'meta' => 'Central knowledge hub'],
                ['title' => 'Version control', 'body' => 'Each reviewed item can now be tracked per member for better onboarding visibility.', 'meta' => 'Audit-friendly updates'],
            ],
            [],
            [
                'columns' => ['Resource', 'Category', 'Audience', 'Format'],
                'rows' => $resources->take(8)->map(fn (MemberResource $resource) => [
                    $resource->title,
                    Str::headline($resource->category),
                    $resource->audience,
                    strtoupper($resource->format),
                ])->all(),
            ],
            $resources->take(4)->map(function (MemberResource $resource) use ($resourceInteractions) {
                $interaction = $resourceInteractions->get($resource->id);

                return [
                    'title' => $resource->title,
                    'body' => $resource->summary,
                    'time' => $interaction?->last_viewed_at?->diffForHumans() ?? 'Not reviewed yet',
                ];
            })->all(),
        ];
    }

    protected function buildEventsPage(array $summary, Collection $events, Collection $registrations, Collection $notifications): array
    {
        $this->eventActions = $events->take(6)->map(function (MemberEvent $event) use ($registrations) {
            $registration = $registrations->get($event->id);

            return [
                'id' => $event->id,
                'title' => $event->title,
                'starts_at' => optional($event->starts_at)->format('M d, Y g:i A') ?? 'TBD',
                'status' => Str::headline($registration?->status ?? 'available'),
                'description' => $event->summary,
                'can_reserve' => ! $registration || $registration->status !== 'reserved',
            ];
        })->all();

        $eventRows = $events->take(8)->map(function (MemberEvent $event) use ($registrations) {
            $registration = $registrations->get($event->id);

            return [
                $event->title,
                optional($event->starts_at)->format('M d, Y g:i A') ?? 'TBD',
                Str::headline($registration?->status ?? 'available'),
            ];
        })->all();

        return [
            [
                ['label' => 'Published Events', 'value' => (string) $summary['publishedEvents']],
                ['label' => 'Reserved Events', 'value' => (string) $summary['reservedEvents']],
                ['label' => 'Reminder Alerts', 'value' => (string) $notifications->whereNull('read_at')->count()],
            ],
            [
                ['title' => 'Upcoming workflow', 'body' => $summary['publishedEvents'] > 0 ? 'Events are now loaded from dedicated event records and can be reserved directly from this page.' : 'No published events yet.', 'meta' => 'Event table is active'],
                ['title' => 'Reminder cadence', 'body' => 'Unread notifications can still be used as the reminder queue for webinars and live labs.', 'meta' => 'In-app reminder stack'],
            ],
            [],
            [
                'columns' => ['Event', 'Logged At', 'State'],
                'rows' => $eventRows,
            ],
            $events->take(4)->map(function (MemberEvent $event) use ($registrations) {
                $registration = $registrations->get($event->id);

                return [
                    'title' => $event->title,
                    'body' => $event->summary,
                    'time' => $registration?->reserved_at?->diffForHumans() ?? (optional($event->starts_at)->diffForHumans() ?? 'Upcoming'),
                ];
            })->all(),
        ];
    }

    protected function buildNotificationsPage(array $summary, Collection $notifications): array
    {
        $this->notificationActions = $notifications->whereNull('read_at')->take(6)->map(fn ($notification) => [
            'id' => $notification->id,
            'title' => data_get($notification->data, 'title', 'Notification'),
            'message' => data_get($notification->data, 'message', 'A new update is available.'),
            'type' => Str::headline((string) data_get($notification->data, 'type', 'system')),
        ])->values()->all();

        return [
            [
                ['label' => 'Unread', 'value' => (string) $summary['unreadNotifications']],
                ['label' => 'Total Notifications', 'value' => (string) $summary['notifications']],
            ],
            [
                ['title' => 'Priority rule', 'body' => 'Unread security, sponsor, and event notices should be handled before general system updates.', 'meta' => 'Current queue size: '.$summary['unreadNotifications']],
                ['title' => 'Preference control', 'body' => 'Use settings to adjust channel delivery without weakening required security notices.', 'meta' => 'Settings remain the source of truth'],
            ],
            [],
            [
                'columns' => ['Notification', 'Type', 'State', 'Received'],
                'rows' => $notifications->take(8)->map(fn ($notification) => [
                    data_get($notification->data, 'title', 'Notification'),
                    Str::headline((string) data_get($notification->data, 'type', 'system')),
                    $notification->read_at ? 'Read' : 'Unread',
                    optional($notification->created_at)->diffForHumans() ?? 'Recently',
                ])->all(),
            ],
            $notifications->take(4)->map(fn ($notification) => [
                'title' => data_get($notification->data, 'title', 'Notification'),
                'body' => data_get($notification->data, 'message', 'A new update is available.'),
                'time' => optional($notification->created_at)->diffForHumans() ?? 'Recently',
            ])->all(),
        ];
    }

    protected function buildSupportPage(User $user, ?SponsorRelationship $relationship, array $summary): array
    {
        $sponsor = $relationship?->sponsor ?? $user->sponsor;

        return [
            [
                ['label' => 'Unread Alerts', 'value' => (string) $summary['unreadNotifications']],
                ['label' => 'Sponsor Contact', 'value' => $sponsor?->email ?? 'Pending assignment'],
            ],
            [
                ['title' => 'Sponsor guidance', 'body' => $sponsor?->username ? 'Start with '.$sponsor->username.' for onboarding and member-growth questions.' : 'A sponsor contact will appear here once assigned.', 'meta' => $sponsor?->profile?->phone_number ?? 'Phone not available'],
                ['title' => 'Security escalation', 'body' => 'Use account security settings first, then raise a support case for suspicious activity.', 'meta' => 'Respond quickly to unread alerts'],
            ],
            [],
            [
                'columns' => ['Support path', 'Use when', 'Owner'],
                'rows' => [
                    ['Sponsor support', 'Invite, onboarding, genealogy questions', $sponsor?->username ?? 'Sponsor desk'],
                    ['Technical support', 'Dashboard access or training issues', 'Operations team'],
                    ['Security support', 'Password or device alerts', 'Security team'],
                ],
            ],
            [],
        ];
    }

    protected function buildSettingsOverviewPage(User $user, array $summary): array
    {
        $profile = $user->profile;

        return [
            [
                ['label' => 'Theme', 'value' => Str::headline((string) ($profile?->theme_preference ?? 'light'))],
                ['label' => 'Unread Alerts', 'value' => (string) $summary['unreadNotifications']],
            ],
            [
                ['title' => 'Profile readiness', 'body' => filled($profile?->phone_number) && filled($profile?->city) ? 'Profile details are ready for sponsor-facing use.' : 'Complete profile fields to strengthen sponsor coordination.', 'meta' => 'Profile and privacy affect member visibility'],
                ['title' => 'Security posture', 'body' => 'Review password and session guidance regularly, especially after device or login alerts.', 'meta' => 'Use the security settings section'],
            ],
            [
                ['title' => 'Settings shortcut', 'body' => 'Use the dedicated settings pages to update profile, notifications, privacy, and theme.', 'meta' => 'Member settings are now live'],
            ],
            [
                'columns' => ['Setting', 'Current signal', 'Route'],
                'rows' => [
                    ['Profile', filled($profile?->phone_number) ? 'Ready' : 'Needs attention', route('member.settings.profile')],
                    ['Notifications', (string) $summary['unreadNotifications'].' unread currently', route('member.settings.notifications')],
                    ['Theme', Str::headline((string) ($profile?->theme_preference ?? 'light')), route('member.settings.theme')],
                ],
            ],
            [],
        ];
    }

    protected function recordActivity(User $user, string $type, string $title, string $description, mixed $subject = null): void
    {
        if (! Schema::hasTable('activities')) {
            return;
        }

        Activity::query()->create([
            'user_id' => $user->id,
            'subject_type' => $subject ? $subject::class : null,
            'subject_id' => $subject?->id,
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'metadata' => ['source' => 'member-content-hub'],
            'ip_address' => request()->ip(),
        ]);
    }
}