<?php

namespace App\Livewire\Admin\Insights;

use App\Models\AuditLog;
use App\Models\Company;
use App\Models\Invitation;
use App\Models\MemberCompanyAffiliation;
use App\Models\MemberResourceInteraction;
use App\Models\OnboardingProgress;
use App\Models\Prospect;
use App\Models\User;
use App\Support\OnboardingSteps;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * "AI Insights" — despite the name (kept as-is; it's the existing page
 * title/route), this app has no LLM/AI integration anywhere. This page
 * was previously 3 hardcoded static text cards. This redesign replaces
 * them with real signals computed from platform data — the same kind of
 * rule-based detection DashboardController::fearOfLossMonitoring()
 * already does for sponsor/company placement gaps (reimplemented here
 * rather than reused, since that method is protected on a controller
 * class not meant to be shared) — each with a severity and, where one
 * exists, a link to the page where an admin can act on it.
 */
class InsightsManager extends Component
{
    #[Url(history: true)]
    public string $severityFilter = '';

    protected function sponsorGapInsight(): array
    {
        $affiliations = MemberCompanyAffiliation::where('status', 'active')->get(['user_id', 'company_id']);
        $joinedByCompany = $affiliations->groupBy('company_id')->map(fn ($rows) => $rows->pluck('user_id')->unique());
        $sponsorIds = User::whereIn('id', $affiliations->pluck('user_id')->unique())->pluck('sponsor_id', 'id');

        $atRiskSponsors = collect();
        $gapsByCompany = [];

        foreach ($affiliations as $affiliation) {
            $sponsorId = $sponsorIds[$affiliation->user_id] ?? null;

            if (! $sponsorId) {
                continue;
            }

            $joined = $joinedByCompany[$affiliation->company_id] ?? collect();

            if (! $joined->contains($sponsorId)) {
                $atRiskSponsors->push($sponsorId);
                $gapsByCompany[$affiliation->company_id] = ($gapsByCompany[$affiliation->company_id] ?? 0) + 1;
            }
        }

        $unmatched = array_sum($gapsByCompany);
        $atRiskCount = $atRiskSponsors->unique()->count();

        $topCompany = null;
        if (! empty($gapsByCompany)) {
            arsort($gapsByCompany);
            $topCompanyId = array_key_first($gapsByCompany);
            $topCompany = Company::find($topCompanyId);
        }

        $detail = $unmatched > 0
            ? "{$atRiskCount} sponsor(s) have a downline member active in a company they haven't joined themselves".($topCompany ? " — most concentrated in {$topCompany->name} ({$gapsByCompany[$topCompany->id]})." : '.')
            : 'Every active member is placed under a sponsor who has also joined the same company.';

        return [
            'key' => 'sponsor-gap',
            'title' => 'Sponsor Placement Gaps',
            'category' => 'Sponsor Coaching',
            'severity' => $unmatched > 0 ? 'warning' : 'good',
            'metric' => $unmatched,
            'metric_label' => $unmatched === 1 ? 'gap' : 'gaps',
            'detail' => $detail,
            'cta_label' => 'Review Downline Tree',
            'cta_route' => route('admin.downline'),
        ];
    }

    protected function staleProspectsInsight(): array
    {
        $hotWarm = Prospect::with('latestActivity')->whereIn('hotness', ['Hot', 'Warm'])->get();

        $stale = $hotWarm->filter(function ($prospect) {
            $lastActivity = $prospect->latestActivity?->action_at;

            return ! $lastActivity || $lastActivity->lt(now()->subDays(14));
        })->count();

        $overdue = Prospect::whereIn('hotness', ['Hot', 'Warm'])
            ->whereNotNull('next_follow_up')
            ->where('next_follow_up', '<', now())
            ->count();

        $total = max(1, $hotWarm->count());
        $pct = round($stale / $total * 100);

        return [
            'key' => 'stale-prospects',
            'title' => 'Stale Hot & Warm Leads',
            'category' => 'Risk Signals',
            'severity' => $stale === 0 ? 'good' : ($pct >= 40 ? 'critical' : 'warning'),
            'metric' => $stale,
            'metric_label' => 'stale',
            'detail' => $stale > 0
                ? "{$stale} of {$hotWarm->count()} hot/warm prospects ({$pct}%) have had no logged activity in 14+ days. {$overdue} also have an overdue follow-up date."
                : 'Every hot or warm prospect has been touched in the last 14 days.',
            'cta_label' => 'Review Follow-ups',
            'cta_route' => route('admin.follow-ups'),
        ];
    }

    protected function onboardingInsight(): array
    {
        $steps = OnboardingSteps::all();
        $totalUsers = max(1, User::count());

        $rows = [];
        $worstStep = null;
        $worstIncomplete = -1;

        foreach ($steps as $step) {
            $completed = OnboardingProgress::where('step_key', $step['key'])->whereNotNull('completed_at')->count();
            $incomplete = $totalUsers - $completed;

            $rows[] = [
                'label' => $step['title'],
                'completed' => $completed,
                'pct' => round($completed / $totalUsers * 100),
            ];

            if ($incomplete > $worstIncomplete) {
                $worstIncomplete = $incomplete;
                $worstStep = $step['title'];
            }
        }

        $usersWithProgress = OnboardingProgress::whereNotNull('completed_at')->select('user_id')->distinct()->pluck('user_id');
        $zeroProgress = User::whereNotIn('id', $usersWithProgress)->count();

        return [
            'key' => 'onboarding',
            'title' => 'Onboarding Progress',
            'category' => 'Education',
            'severity' => $zeroProgress > $totalUsers * 0.3 ? 'critical' : ($zeroProgress > 0 ? 'warning' : 'good'),
            'metric' => $zeroProgress,
            'metric_label' => 'at zero',
            'detail' => "{$zeroProgress} of {$totalUsers} members haven't completed a single onboarding step. The biggest drop-off is \"{$worstStep}\".",
            'breakdown' => $rows,
        ];
    }

    protected function resourceEngagementInsight(): array
    {
        $activeMembers = User::where('status', 'active')->pluck('id');
        $engaged = MemberResourceInteraction::whereIn('user_id', $activeMembers)->distinct('user_id')->count('user_id');
        $total = max(1, $activeMembers->count());
        $notEngaged = $total - $engaged;
        $pct = round($notEngaged / $total * 100);

        return [
            'key' => 'resource-engagement',
            'title' => 'Resource Engagement Gap',
            'category' => 'Education',
            'severity' => $pct >= 50 ? 'critical' : ($notEngaged > 0 ? 'warning' : 'good'),
            'metric' => $notEngaged,
            'metric_label' => 'never engaged',
            'detail' => $notEngaged > 0
                ? "{$notEngaged} of {$total} active members ({$pct}%) have never opened a published resource."
                : 'Every active member has engaged with at least one resource.',
        ];
    }

    protected function invitationHealthInsight(): array
    {
        $expiringSoon = Invitation::whereIn('status', ['sent', 'pending'])
            ->whereNotNull('expires_at')
            ->whereBetween('expires_at', [now(), now()->addHours(48)])
            ->count();

        $stale = Invitation::whereIn('status', ['sent', 'pending'])
            ->whereNull('accepted_at')
            ->where('created_at', '<=', now()->subDays(7))
            ->count();

        $flagged = $expiringSoon + $stale;

        return [
            'key' => 'invitation-health',
            'title' => 'Invitation Health',
            'category' => 'Risk Signals',
            'severity' => $flagged === 0 ? 'good' : 'warning',
            'metric' => $flagged,
            'metric_label' => 'flagged',
            'detail' => $flagged > 0
                ? "{$expiringSoon} invitation(s) expire within 48 hours and {$stale} have sat unanswered for a week or more."
                : 'No pending invitations are expiring soon or going stale.',
        ];
    }

    protected function securityInsight(): array
    {
        $sensitiveEvents = ['member.role_changed', 'member.status_changed', 'member.password_reset', 'sponsor.reassigned'];

        $recent = AuditLog::whereIn('event', $sensitiveEvents)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        return [
            'key' => 'security',
            'title' => 'Sensitive Admin Activity',
            'category' => 'Risk Signals',
            'severity' => 'info',
            'metric' => $recent,
            'metric_label' => 'this week',
            'detail' => "{$recent} role, status, password, or sponsor change(s) logged in the last 7 days. Note: failed-login attempts aren't currently persisted anywhere, so they can't be surfaced here.",
            'cta_label' => 'Open Audit Logs',
            'cta_route' => route('admin.audit-logs.index'),
        ];
    }

    protected function allInsights(): array
    {
        return [
            $this->sponsorGapInsight(),
            $this->staleProspectsInsight(),
            $this->onboardingInsight(),
            $this->resourceEngagementInsight(),
            $this->invitationHealthInsight(),
            $this->securityInsight(),
        ];
    }

    protected function severityCounts(array $all): array
    {
        $counts = ['critical' => 0, 'warning' => 0, 'info' => 0, 'good' => 0];

        foreach ($all as $insight) {
            $counts[$insight['severity']] = ($counts[$insight['severity']] ?? 0) + 1;
        }

        return $counts;
    }

    public function render()
    {
        $all = $this->allInsights();

        $filtered = $this->severityFilter !== ''
            ? array_values(array_filter($all, fn ($i) => $i['severity'] === $this->severityFilter))
            : $all;

        return view('livewire.admin.insights.insights-manager', [
            'insights' => $filtered,
            'counts' => $this->severityCounts($all),
        ]);
    }
}
