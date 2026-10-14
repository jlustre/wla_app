<?php

namespace App\Livewire\Admin\ActivityLogs;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Read-only admin view of the activities table. Unlike AuditLog (an
 * invisible compliance trail nothing else reads), each Activity row is
 * also shown back to the member it belongs to on their own dashboard
 * ("Recent Activity" timeline, event summary — see
 * App\Livewire\Dashboard\DashboardHome::loadActivities()). Editing or
 * deleting a row here would silently rewrite what a real member sees
 * about their own history, so — same as Audit Logs — this stays
 * filter/search/paginate/export/drill-down only, no CRUD.
 */
class ActivityLogsManager extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $typeFilter = '';

    #[Url(history: true)]
    public string $actorFilter = '';

    #[Url(history: true)]
    public string $dateFrom = '';

    #[Url(history: true)]
    public string $dateTo = '';

    public string $sortBy = 'created_at';

    public string $sortDir = 'desc';

    public int $perPage = 20;

    public ?int $viewingId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    public function updatingActorFilter(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'typeFilter', 'actorFilter', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function toggleSort(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDir = 'asc';
        }
    }

    public function viewDetails(int $id): void
    {
        $this->viewingId = Activity::findOrFail($id)->id;
    }

    public function closeDetails(): void
    {
        $this->viewingId = null;
    }

    public static function typeLabel(?string $type): string
    {
        return $type ? Str::headline(str_replace(['.', '_'], ' ', $type)) : 'Unknown';
    }

    public static function typeBadgeClass(?string $type): string
    {
        return match ($type) {
            'success', 'member' => 'bg-emerald-100 text-emerald-700',
            'info' => 'bg-sky-100 text-sky-700',
            'danger' => 'bg-rose-100 text-rose-700',
            'warning', 'presentation' => 'bg-amber-100 text-amber-700',
            'invite' => 'bg-indigo-100 text-indigo-700',
            'event' => 'bg-violet-100 text-violet-700',
            'resource' => 'bg-teal-100 text-teal-700',
            default => 'bg-slate-100 text-slate-600',
        };
    }

    public static function subjectLabel(?string $type): string
    {
        if (! $type) {
            return '—';
        }

        return class_basename($type);
    }

    protected function filteredQuery()
    {
        $query = Activity::query()->with('user');

        if (trim($this->search) !== '') {
            $term = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('description', 'like', $term)
                    ->orWhere('type', 'like', $term)
                    ->orWhereHas('user', fn ($u) => $u->where('username', 'like', $term)->orWhere('email', 'like', $term));
            });
        }

        if ($this->typeFilter !== '') {
            $query->where('type', $this->typeFilter);
        }

        if ($this->actorFilter !== '') {
            $query->where('user_id', $this->actorFilter);
        }

        if ($this->dateFrom !== '') {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo !== '') {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        return $query;
    }

    protected function rows()
    {
        $sortable = ['created_at', 'type', 'title'];
        $sortBy = in_array($this->sortBy, $sortable, true) ? $this->sortBy : 'created_at';

        return $this->filteredQuery()->orderBy($sortBy, $this->sortDir)->paginate($this->perPage);
    }

    protected function stats(): array
    {
        return [
            'total' => Activity::count(),
            'today' => Activity::whereDate('created_at', now()->toDateString())->count(),
            'this_week' => Activity::where('created_at', '>=', now()->subDays(7))->count(),
            'unique_actors' => Activity::whereNotNull('user_id')->distinct('user_id')->count('user_id'),
        ];
    }

    protected function filterOptions(): array
    {
        return [
            'types' => Activity::select('type')->distinct()->orderBy('type')->pluck('type')->all(),
            'actors' => User::whereIn('id', Activity::whereNotNull('user_id')->select('user_id')->distinct())->orderBy('username')->get(['id', 'username']),
        ];
    }

    /**
     * Streams the currently filtered result set (capped at 5,000 rows) as
     * CSV — everything matching the filters, not just the current page.
     */
    public function export()
    {
        $rows = $this->filteredQuery()->orderBy('created_at', 'desc')->limit(5000)->get();

        $filename = 'activity-logs-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['When', 'User', 'Type', 'Title', 'Description', 'Subject Type', 'Subject ID', 'Metadata', 'IP Address']);

            foreach ($rows as $activity) {
                fputcsv($out, [
                    optional($activity->created_at)->toDateTimeString(),
                    $activity->user?->username ?? 'System',
                    $activity->type,
                    $activity->title,
                    $activity->description,
                    self::subjectLabel($activity->subject_type),
                    $activity->subject_id,
                    json_encode($activity->metadata),
                    $activity->ip_address,
                ]);
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function render()
    {
        return view('livewire.admin.activity-logs.activity-logs-manager', [
            'rows' => $this->rows(),
            'stats' => $this->stats(),
            'filterOptions' => $this->filterOptions(),
            'viewingActivity' => $this->viewingId
                ? Activity::with('user')->find($this->viewingId)
                : null,
        ]);
    }
}
