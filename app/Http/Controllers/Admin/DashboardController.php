<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Company;
use App\Models\Invitation;
use App\Models\MemberCompanyAffiliation;
use App\Models\MemberResource;
use App\Models\SponsorRelationship;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();

        $kpis = $this->buildKpis($now);
        $companies = $this->topCompanies();
        $growthWeeks = $this->weeklyGrowth($now);
        $fearOfLoss = $this->fearOfLossMonitoring();
        $activityFeed = $this->recentActivityFeed();
        $memberRows = $this->memberSnapshot();
        $reassignments = $this->reassignmentAudit();
        $resources = MemberResource::latest()->take(4)->get();
        $announcements = $this->announcements($kpis, $fearOfLoss, $resources);

        return view('admin-dashboard', [
            'kpis' => $kpis,
            'companies' => $companies,
            'growthWeeks' => $growthWeeks,
            'fearOfLoss' => $fearOfLoss,
            'activityFeed' => $activityFeed,
            'memberRows' => $memberRows,
            'reassignments' => $reassignments,
            'resources' => $resources,
            'announcements' => $announcements,
        ]);
    }

    protected function buildKpis(Carbon $now): array
    {
        $totalMembers = User::count();
        $activeMembers = User::where('status', 'active')->count();
        $activeRate = $totalMembers > 0 ? round($activeMembers / $totalMembers * 100, 1) : 0.0;
        $newThisWeek = User::where('created_at', '>=', $now->copy()->subWeek())->count();
        $newSinceYesterday = User::where('created_at', '>=', $now->copy()->subDay())->count();

        $totalCompanies = Company::count();
        $categoryCount = Company::whereNotNull('category')->distinct()->count('category');
        $activeMemberships = MemberCompanyAffiliation::where('status', 'active')->count();

        $pendingInvitations = Invitation::whereIn('status', ['draft', 'sent', 'pending'])->count();
        $expiringSoon = Invitation::whereIn('status', ['sent', 'pending'])
            ->whereNotNull('expires_at')
            ->whereBetween('expires_at', [$now, $now->copy()->addHours(48)])
            ->count();
        $totalInvitations = Invitation::count();
        $acceptedInvitations = Invitation::where('status', 'accepted')->count();
        $conversionRate = $totalInvitations > 0 ? round($acceptedInvitations / $totalInvitations * 100, 1) : 0.0;

        $totalReassignments = SponsorRelationship::where('is_current', false)->count();
        $reassignmentsThisWeek = SponsorRelationship::where('is_current', false)
            ->where('ended_at', '>=', $now->copy()->subWeek())
            ->count();

        $totalResources = MemberResource::count();
        $resourcesThisWeek = MemberResource::where('created_at', '>=', $now->copy()->subWeek())->count();

        return compact(
            'totalMembers', 'activeMembers', 'activeRate', 'newThisWeek', 'newSinceYesterday',
            'totalCompanies', 'categoryCount', 'activeMemberships',
            'pendingInvitations', 'expiringSoon', 'totalInvitations', 'acceptedInvitations', 'conversionRate',
            'totalReassignments', 'reassignmentsThisWeek',
            'totalResources', 'resourcesThisWeek',
        );
    }

    protected function topCompanies()
    {
        return Company::query()
            ->withCount(['memberAffiliations as joined_members_count' => function ($query) {
                $query->where('status', 'active');
            }])
            ->orderByDesc('joined_members_count')
            ->take(4)
            ->get();
    }

    protected function weeklyGrowth(Carbon $now)
    {
        $weeks = collect(range(9, 0));

        $counts = $weeks->map(function (int $weeksAgo) use ($now) {
            $weekStart = $now->copy()->subWeeks($weeksAgo)->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();

            return [
                'label' => 'W'.(10 - $weeksAgo),
                'count' => User::whereBetween('created_at', [$weekStart, $weekEnd])->count(),
            ];
        });

        $max = max(1, $counts->max('count'));

        return $counts->map(function (array $week) use ($max) {
            $week['percent'] = (int) round($week['count'] / $max * 100);

            return $week;
        });
    }

    /**
     * Detects real "fear of loss" positioning gaps: members who joined an
     * affiliated company while their own sponsor has not joined that same
     * company, per the WLA sponsorship-positioning rules.
     */
    protected function fearOfLossMonitoring(): array
    {
        $activeAffiliations = MemberCompanyAffiliation::where('status', 'active')
            ->get(['user_id', 'company_id']);

        $joinedByCompany = $activeAffiliations
            ->groupBy('company_id')
            ->map(fn ($rows) => $rows->pluck('user_id')->flip());

        $sponsorIdByUserId = User::pluck('sponsor_id', 'id');

        $atRiskSponsorIds = [];
        $gapsByCompany = [];
        $unmatchedCount = 0;

        foreach ($activeAffiliations as $affiliation) {
            $sponsorId = $sponsorIdByUserId[$affiliation->user_id] ?? null;

            if (! $sponsorId) {
                continue;
            }

            $joinedUserIds = $joinedByCompany->get($affiliation->company_id, collect());

            if (! $joinedUserIds->has($sponsorId)) {
                $atRiskSponsorIds[$sponsorId] = true;
                $gapsByCompany[$affiliation->company_id] = ($gapsByCompany[$affiliation->company_id] ?? 0) + 1;
                $unmatchedCount++;
            }
        }

        $topGapCompany = null;
        $topGapCompanyCount = 0;

        if ($gapsByCompany !== []) {
            $topGapCompanyId = array_search(max($gapsByCompany), $gapsByCompany, true);
            $topGapCompany = Company::find($topGapCompanyId);
            $topGapCompanyCount = $gapsByCompany[$topGapCompanyId];
        }

        return [
            'membersAtRisk' => count($atRiskSponsorIds),
            'unmatchedCount' => $unmatchedCount,
            'topGapCompany' => $topGapCompany,
            'topGapCompanyCount' => $topGapCompanyCount,
        ];
    }

    protected function recentActivityFeed()
    {
        $feed = collect();

        User::with('sponsor')->latest()->take(5)->get()->each(function (User $user) use (&$feed) {
            $feed->push([
                'dot' => 'bg-emerald-400',
                'title' => $user->username.' joined WLA',
                'description' => $user->sponsor
                    ? 'Invitation accepted under '.$user->sponsor->username
                    : 'Registered directly',
                'time' => $user->created_at,
            ]);
        });

        MemberCompanyAffiliation::with(['user', 'company'])
            ->where('status', 'active')
            ->latest('joined_at')
            ->take(5)
            ->get()
            ->each(function (MemberCompanyAffiliation $affiliation) use (&$feed) {
                if (! $affiliation->user || ! $affiliation->company) {
                    return;
                }

                $feed->push([
                    'dot' => 'bg-sky-400',
                    'title' => $affiliation->user->username.' joined '.$affiliation->company->name,
                    'description' => 'Company membership recorded successfully',
                    'time' => $affiliation->joined_at ?? $affiliation->created_at,
                ]);
            });

        SponsorRelationship::with('member')
            ->where('is_current', false)
            ->latest('ended_at')
            ->take(5)
            ->get()
            ->each(function (SponsorRelationship $relationship) use (&$feed) {
                if (! $relationship->member) {
                    return;
                }

                $feed->push([
                    'dot' => 'bg-amber-400',
                    'title' => 'Sponsorship reassigned for '.$relationship->member->username,
                    'description' => data_get($relationship->metadata, 'reason', 'Original sponsor relationship ended'),
                    'time' => $relationship->ended_at ?? $relationship->updated_at,
                ]);
            });

        MemberResource::latest()->take(3)->get()->each(function (MemberResource $resource) use (&$feed) {
            $feed->push([
                'dot' => 'bg-cyan-400',
                'title' => 'New resource uploaded',
                'description' => $resource->title,
                'time' => $resource->created_at,
            ]);
        });

        AuditLog::with('actor')->latest()->take(3)->get()->each(function (AuditLog $log) use (&$feed) {
            $feed->push([
                'dot' => 'bg-sky-300',
                'title' => Str::headline($log->event),
                'description' => $log->actor ? 'By '.$log->actor->username : 'System action',
                'time' => $log->created_at,
            ]);
        });

        return $feed->filter(fn ($item) => $item['time'] !== null)
            ->sortByDesc('time')
            ->take(6)
            ->values();
    }

    protected function memberSnapshot()
    {
        return User::with('sponsor')
            ->withCount([
                'companyAffiliations as active_companies_count' => function ($query) {
                    $query->where('status', 'active');
                },
                'directMembers',
            ])
            ->latest()
            ->take(8)
            ->get();
    }

    protected function reassignmentAudit()
    {
        return SponsorRelationship::with(['member.sponsor', 'sponsor'])
            ->where('is_current', false)
            ->latest('ended_at')
            ->take(6)
            ->get()
            ->map(function (SponsorRelationship $old) {
                return [
                    'date' => optional($old->ended_at ?? $old->updated_at)->format('M d, Y'),
                    'member' => $old->member?->username ?? 'Unknown member',
                    'original_sponsor' => $old->sponsor?->username ?? 'Unknown sponsor',
                    'new_sponsor' => $old->member?->sponsor?->username ?? '—',
                    'reason' => data_get($old->metadata, 'reason', 'Sponsor relationship updated'),
                ];
            });
    }

    protected function announcements(array $kpis, array $fearOfLoss, $resources): array
    {
        $items = [];

        if ($fearOfLoss['topGapCompany']) {
            $items[] = [
                'tone' => 'warning',
                'title' => 'Positioning review recommended for '.$fearOfLoss['topGapCompany']->name,
                'description' => $fearOfLoss['topGapCompanyCount'].' sponsor positioning gap(s) detected for this company.',
            ];
        }

        if ($kpis['expiringSoon'] > 0) {
            $items[] = [
                'tone' => 'default',
                'title' => 'Invitation cleanup scheduled',
                'description' => $kpis['expiringSoon'].' pending invitation(s) will expire within the next 48 hours.',
            ];
        }

        if ($kpis['resourcesThisWeek'] > 0) {
            $items[] = [
                'tone' => 'default',
                'title' => $kpis['resourcesThisWeek'].' new resource(s) published this week',
                'description' => 'Verify link assignments and featured order in the resource library.',
            ];
        }

        $items[] = [
            'tone' => 'default',
            'title' => 'Platform snapshot',
            'description' => $kpis['totalMembers'].' total members across '.$kpis['totalCompanies'].' affiliated companies, '.$kpis['activeMemberships'].' active company memberships.',
        ];

        return array_slice($items, 0, 4);
    }
}
