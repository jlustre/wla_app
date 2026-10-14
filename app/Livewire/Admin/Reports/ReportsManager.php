<?php

namespace App\Livewire\Admin\Reports;

use App\Livewire\Admin\AuditLogs\AuditLogsManager;
use App\Models\AuditLog;
use App\Models\Company;
use App\Models\Prospect;
use App\Models\SponsorRelationship;
use App\Models\Timeline;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Attributes\Url;
use Livewire\Component;
use Spatie\Permission\Models\Role;

/**
 * Admin analytics dashboard. Read-only — no CRUD, this only aggregates.
 * Deliberately avoids re-showing what the main admin dashboard
 * (DashboardController) already covers (total members, active rate,
 * company count, invitation conversion, a 10-week member signup chart);
 * instead goes deeper on breakdowns the dashboard doesn't have room for:
 * the prospect funnel/source/hotness mix, member role distribution,
 * sponsor relationship health, and audit activity — each linking back
 * into the relevant management page for drill-down.
 */
class ReportsManager extends Component
{
    #[Url(history: true)]
    public string $range = '30';

    protected function dateRange(): array
    {
        $end = now()->endOfDay();

        $start = match ($this->range) {
            '7' => now()->subDays(6)->startOfDay(),
            '14' => now()->subDays(13)->startOfDay(),
            '90' => now()->subDays(89)->startOfDay(),
            'all' => Carbon::parse(min(User::min('created_at') ?? now(), Prospect::min('created_at') ?? now()))->startOfDay(),
            default => now()->subDays(29)->startOfDay(),
        };

        // Cap the plotted range at 90 days even for "all" so the chart
        // stays legible — older history is still reflected in the
        // all-time breakdown tiles, just not every day on the line chart.
        if ($start->lt(now()->subDays(89)->startOfDay())) {
            $start = now()->subDays(89)->startOfDay();
        }

        return [$start, $end];
    }

    protected function overview(): array
    {
        return [
            'members' => User::count(),
            'prospects' => Prospect::count(),
            'companies' => Company::count(),
            'sponsor_current' => SponsorRelationship::where('is_current', true)->count(),
        ];
    }

    /**
     * Daily new-members vs new-prospects over the selected range, with
     * SVG point coordinates precomputed so the Blade view stays simple.
     */
    protected function trend(): array
    {
        [$start, $end] = $this->dateRange();

        $memberCounts = User::query()
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('d')
            ->pluck('c', 'd');

        $prospectCounts = Prospect::query()
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('d')
            ->pluck('c', 'd');

        $labels = [];
        $isoDates = [];
        $members = [];
        $prospects = [];

        foreach (CarbonPeriod::create($start, $end) as $day) {
            $key = $day->format('Y-m-d');
            $isoDates[] = $day->format('M j, Y');
            $labels[] = $day->format('M j');
            $members[] = (int) ($memberCounts[$key] ?? 0);
            $prospects[] = (int) ($prospectCounts[$key] ?? 0);
        }

        $max = max(1, max($members ?: [0]), max($prospects ?: [0]));
        $width = 640;
        $height = 220;
        $padX = 16;
        $padY = 12;
        $n = count($labels);
        $stepX = $n > 1 ? ($width - 2 * $padX) / ($n - 1) : 0;

        $toPoints = function (array $values) use ($n, $stepX, $padX, $padY, $width, $height, $max) {
            $points = [];
            for ($i = 0; $i < $n; $i++) {
                $x = $padX + $i * $stepX;
                $y = $height - $padY - ($max > 0 ? ($values[$i] / $max) * ($height - 2 * $padY) : 0);
                $points[] = round($x, 1).','.round($y, 1);
            }

            return implode(' ', $points);
        };

        return [
            'labels' => $labels,
            'isoDates' => $isoDates,
            'members' => $members,
            'prospects' => $prospects,
            'memberPoints' => $toPoints($members),
            'prospectPoints' => $toPoints($prospects),
            'max' => $max,
            'width' => $width,
            'height' => $height,
            'padX' => $padX,
            'padY' => $padY,
            'stepX' => $stepX,
            'totalMembers' => array_sum($members),
            'totalProspects' => array_sum($prospects),
        ];
    }

    /**
     * Where every prospect currently sits in the funnel — a point-in-time
     * snapshot (not range-filtered), reusing the exact stage/color
     * definitions the Leads Pipeline Kanban board already uses so the two
     * pages read as the same system.
     */
    protected function funnelBreakdown(): array
    {
        $stageOptions = Timeline::funnelStageOptions();
        $counts = array_fill_keys(array_keys($stageOptions), 0);

        Prospect::with('latestActivity')->get(['id'])->each(function ($prospect) use (&$counts) {
            $stage = Timeline::funnelStageForAction($prospect->latestActivity?->action);
            $counts[$stage] = ($counts[$stage] ?? 0) + 1;
        });

        $total = max(1, array_sum($counts));

        $rows = [];
        foreach ($stageOptions as $stage => $meta) {
            $rows[] = [
                'key' => $stage,
                'label' => $meta['label'],
                'color' => $meta['color'],
                'count' => $counts[$stage],
                'pct' => round($counts[$stage] / $total * 100, 1),
            ];
        }

        return $rows;
    }

    protected function normalizeSource(?string $source): string
    {
        $source = trim((string) $source);

        if ($source === '') {
            return 'Unknown';
        }

        if (in_array(strtolower($source), ['twitter', 'twitter / x', 'x'], true)) {
            return 'Twitter / X';
        }

        return $source;
    }

    protected function sourceBreakdown(): array
    {
        $raw = Prospect::select('source')->get()->countBy(fn ($p) => $this->normalizeSource($p->source));

        $total = max(1, $raw->sum());

        return $raw->sortDesc()->take(8)->map(fn ($count, $label) => [
            'label' => $label,
            'count' => $count,
            'pct' => round($count / $total * 100, 1),
        ])->values()->all();
    }

    protected function hotnessBreakdown(): array
    {
        $counts = Prospect::select('hotness')->get()->countBy(fn ($p) => $p->hotness ?: 'Not set');
        $total = max(1, $counts->sum());

        $order = ['Hot' => 'rose', 'Warm' => 'amber', 'Cold' => 'sky', 'Inactive' => 'slate', 'N/A' => 'slate', 'Not set' => 'slate'];

        $rows = [];
        foreach ($order as $label => $color) {
            if (! $counts->has($label)) {
                continue;
            }

            $rows[] = ['label' => $label, 'color' => $color, 'count' => $counts[$label], 'pct' => round($counts[$label] / $total * 100, 1)];
        }

        foreach ($counts as $label => $count) {
            if (! array_key_exists($label, $order)) {
                $rows[] = ['label' => $label, 'color' => 'slate', 'count' => $count, 'pct' => round($count / $total * 100, 1)];
            }
        }

        return $rows;
    }

    protected function roleBreakdown(): array
    {
        $roleCounts = Role::withCount('users')->orderByDesc('users_count')->get();
        $noRole = User::doesntHave('roles')->count();
        $total = max(1, User::count());

        $rows = $roleCounts->map(fn ($role) => [
            'label' => $role->name,
            'count' => $role->users_count,
            'pct' => round($role->users_count / $total * 100, 1),
        ])->all();

        if ($noRole > 0) {
            $rows[] = ['label' => 'No role assigned', 'count' => $noRole, 'pct' => round($noRole / $total * 100, 1)];
        }

        return $rows;
    }

    protected function sponsorStats(): array
    {
        [$start, $end] = $this->dateRange();

        return [
            'current' => SponsorRelationship::where('is_current', true)->count(),
            'ended' => SponsorRelationship::where('is_current', false)->count(),
            'reassigned_in_range' => SponsorRelationship::where('is_current', false)->whereBetween('ended_at', [$start, $end])->count(),
        ];
    }

    protected function companyCategoryBreakdown(): array
    {
        $counts = Company::select('category')->get()->countBy(fn ($c) => $c->category ?: 'Uncategorized');

        return $counts->sortDesc()->map(fn ($count, $label) => ['label' => $label, 'count' => $count])->values()->all();
    }

    protected function auditEventBreakdown(): array
    {
        [$start, $end] = $this->dateRange();

        $counts = AuditLog::whereBetween('created_at', [$start, $end])
            ->select('event')
            ->get()
            ->countBy('event');

        return $counts->sortDesc()->map(fn ($count, $event) => [
            'event' => $event,
            'label' => AuditLogsManager::eventLabel($event),
            'count' => $count,
        ])->values()->all();
    }

    public function render()
    {
        return view('livewire.admin.reports.reports-manager', [
            'overview' => $this->overview(),
            'trend' => $this->trend(),
            'funnel' => $this->funnelBreakdown(),
            'sources' => $this->sourceBreakdown(),
            'hotness' => $this->hotnessBreakdown(),
            'roles' => $this->roleBreakdown(),
            'sponsor' => $this->sponsorStats(),
            'companyCategories' => $this->companyCategoryBreakdown(),
            'auditEvents' => $this->auditEventBreakdown(),
        ]);
    }
}
