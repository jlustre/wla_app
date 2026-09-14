<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\AuditLog;
use App\Models\Company;
use App\Models\Invitation;
use App\Models\Prospect;
use App\Models\SponsorRelationship;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class WorkspaceController extends Controller
{
    public function users(): View
    {
        $users = User::query()
            ->with(['profile', 'roles', 'sponsor'])
            ->latest()
            ->paginate(20);

        return view('admin.workspace.users', compact('users'));
    }

    public function sponsorRelationships(): View
    {
        $relationships = SponsorRelationship::query()
            ->with(['member', 'sponsor'])
            ->latest('linked_at')
            ->paginate(20);

        return view('admin.workspace.sponsor-relationships', compact('relationships'));
    }

    public function auditLogs(): View
    {
        $logs = AuditLog::query()
            ->with('actor')
            ->latest()
            ->paginate(20);

        return view('admin.workspace.audit-logs', compact('logs'));
    }

    public function settings(): View
    {
        return view('admin.workspace.settings', [
            'appName' => config('app.name'),
            'timezone' => config('app.timezone'),
            'environment' => config('app.env'),
            'companyCount' => Company::query()->count(),
            'memberCount' => User::query()->count(),
        ]);
    }

    public function roles(): View
    {
        $roles = Role::query()
            ->withCount('users')
            ->orderBy('name')
            ->get()
            ->map(fn (Role $role) => [
                $role->name,
                (string) $role->users_count,
                $role->guard_name,
            ]);

        return $this->panel(
            'Role Management',
            'Review platform roles and how many accounts are assigned to each one.',
            ['Role', 'Users', 'Guard'],
            $roles,
        );
    }

    public function prospects(): View
    {
        $prospects = Prospect::query()
            ->with('user')
            ->latest()
            ->paginate(20);

        return $this->panel(
            'Prospects',
            'Inspect every prospect captured across the member CRM.',
            ['Name', 'Email', 'Phone', 'Owner', 'Source'],
            $prospects->through(fn (Prospect $prospect) => [
                trim($prospect->first_name.' '.$prospect->last_name) ?: 'Unnamed',
                $prospect->email ?: '—',
                $prospect->phone ?: '—',
                $prospect->user?->username ?? 'Unassigned',
                $prospect->source ?: '—',
            ]),
            $prospects,
        );
    }

    public function pipeline(): View
    {
        $prospects = Prospect::query()
            ->with('user')
            ->orderByDesc('hotness')
            ->paginate(20);

        return $this->panel(
            'Leads Pipeline',
            'Rank prospects by hotness so admins can see where follow-up is most needed.',
            ['Name', 'Owner', 'Hotness', 'Last action'],
            $prospects->through(fn (Prospect $prospect) => [
                trim($prospect->first_name.' '.$prospect->last_name) ?: 'Unnamed',
                $prospect->user?->username ?? 'Unassigned',
                (string) ($prospect->hotness ?? 0),
                $prospect->last_action ?: '—',
            ]),
            $prospects,
        );
    }

    public function followUps(): View
    {
        $invites = Schema::hasTable('invitations')
            ? Invitation::query()->with('inviter')->whereIn('status', ['pending', 'sent'])->latest('sent_at')->paginate(20)
            : $this->emptyPaginator();

        return $this->panel(
            'Follow-ups',
            'Pending invitations that still need a sponsor or admin follow-up.',
            ['Invitee', 'Inviter', 'Status', 'Sent'],
            $invites->through(fn (Invitation $invite) => [
                $invite->email ?: ($invite->phone ?: 'Unknown'),
                $invite->inviter?->username ?? 'Unknown',
                ucfirst((string) $invite->status),
                optional($invite->sent_at)->format('M d, Y') ?: 'Not sent',
            ]),
            $invites,
        );
    }

    public function activityLogs(): View
    {
        $activities = Schema::hasTable('activities')
            ? Activity::query()->with('user')->latest()->paginate(20)
            : $this->emptyPaginator();

        return $this->panel(
            'Activity Logs',
            'Recent member and admin activity across the platform.',
            ['When', 'User', 'Type', 'Title'],
            $activities->through(fn (Activity $activity) => [
                optional($activity->created_at)->format('M d, Y g:i A') ?: '—',
                $activity->user?->username ?? 'System',
                ucfirst((string) $activity->type),
                $activity->title ?: '—',
            ]),
            $activities,
        );
    }

    public function notifications(): View
    {
        $notifications = Schema::hasTable('notifications')
            ? DB::table('notifications')->latest()->paginate(20)
            : $this->emptyPaginator();

        return $this->panel(
            'Notifications',
            'Platform notifications sent to members and administrators.',
            ['When', 'Type', 'Recipient', 'Read'],
            $notifications->through(fn ($notification) => [
                $notification->created_at ? \Illuminate\Support\Carbon::parse($notification->created_at)->format('M d, Y g:i A') : '—',
                class_basename((string) $notification->type),
                $notification->notifiable_id ?: '—',
                $notification->read_at ? 'Read' : 'Unread',
            ]),
            $notifications,
        );
    }

    public function reports(): View
    {
        return view('admin.workspace.panel', [
            'title' => 'Reports and Analytics',
            'description' => 'High-level operating metrics for members, companies, invitations, and sponsorship links.',
            'metrics' => [
                ['label' => 'Members', 'value' => User::query()->count()],
                ['label' => 'Companies', 'value' => Company::query()->count()],
                ['label' => 'Prospects', 'value' => Prospect::query()->count()],
                ['label' => 'Current sponsor links', 'value' => SponsorRelationship::query()->where('is_current', true)->count()],
            ],
            'cards' => [
                ['title' => 'Network health', 'body' => 'Use sponsor relationships, audit logs, and member management together to keep placement and compliance intact.'],
                ['title' => 'Growth', 'body' => 'Compare pending invitations against active members to see where follow-up will move the pipeline.'],
            ],
        ]);
    }

    public function aiInsights(): View
    {
        return view('admin.workspace.panel', [
            'title' => 'AI Insights',
            'description' => 'Operational prompts for member education, sponsor coaching, and engagement.',
            'cards' => [
                ['title' => 'Sponsor coaching', 'body' => 'Flag members with pending invites and incomplete training so upline follow-up stays inside approved channels.'],
                ['title' => 'Risk signals', 'body' => 'Watch for inactive sponsors, stalled prospects, and unread security notifications before they affect placement quality.'],
                ['title' => 'Education', 'body' => 'Prioritize members who have not finished core education or reviewed published resources.'],
            ],
        ]);
    }

    public function downline(): View
    {
        return view('admin.workspace.downline', [
            'title' => 'Downline Tree',
            'description' => 'Inspect the live sponsorship explorer, then use placement and spillover pages for the operating rules behind it.',
        ]);
    }

    public function placementRules(): View
    {
        return view('admin.workspace.panel', [
            'title' => 'Placement Rules',
            'description' => 'How WLA places new members under a qualified sponsor without breaking company-specific downline rules.',
            'cards' => [
                ['title' => 'Sponsor first', 'body' => 'New members attach to the inviting sponsor when that sponsor is active and eligible for the company being joined.'],
                ['title' => 'Company eligibility', 'body' => 'Placement is evaluated per affiliated company so a member can sit correctly in more than one compensation tree.'],
                ['title' => 'Admin override', 'body' => 'Reassignments are recorded in audit logs and should keep a clear original sponsor, new sponsor, and reason.'],
            ],
        ]);
    }

    public function spillover(): View
    {
        return view('admin.workspace.panel', [
            'title' => 'Spillover Logic',
            'description' => 'How overflow is handled when a sponsor’s available placement slots are already filled.',
            'cards' => [
                ['title' => 'Qualified overflow', 'body' => 'Spillover only moves to the next eligible downline seat after the original sponsor relationship has been recorded.'],
                ['title' => 'No silent skips', 'body' => 'Skipped uplines and company-specific exceptions must remain visible in genealogy and audit history.'],
                ['title' => 'Future company rules', 'body' => 'Each affiliated company can later supply its own spillover depth without changing the core WLA sponsor link.'],
            ],
        ]);
    }

    protected function panel(string $title, string $description, array $columns, $rows, $paginator = null): View
    {
        return view('admin.workspace.panel', [
            'title' => $title,
            'description' => $description,
            'columns' => $columns,
            'rows' => $rows,
            'paginator' => $paginator,
        ]);
    }

    protected function emptyPaginator(): \Illuminate\Pagination\LengthAwarePaginator
    {
        return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
    }
}
