<?php

namespace App\Livewire\Admin\AuditLogs;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Read-only admin view of the audit_logs table. Audit trails are
 * intentionally never editable or deletable from here — nothing in the
 * app ever mutates a written AuditLog row, and an admin UI that could
 * edit/delete entries would defeat the point of keeping them. This page
 * is filter/search/paginate/export/drill-down only.
 */
class AuditLogsManager extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $eventFilter = '';

    #[Url(history: true)]
    public string $actorFilter = '';

    #[Url(history: true)]
    public string $typeFilter = '';

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

    public function updatingEventFilter(): void
    {
        $this->resetPage();
    }

    public function updatingActorFilter(): void
    {
        $this->resetPage();
    }

    public function updatingTypeFilter(): void
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
        $this->reset(['search', 'eventFilter', 'actorFilter', 'typeFilter', 'dateFrom', 'dateTo']);
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
        $this->viewingId = AuditLog::findOrFail($id)->id;
    }

    public function closeDetails(): void
    {
        $this->viewingId = null;
    }

    public static function eventLabel(?string $event): string
    {
        return $event ? Str::headline(str_replace(['.', '_'], ' ', $event)) : 'Unknown';
    }

    public static function auditableLabel(?string $type): string
    {
        if (! $type) {
            return '—';
        }

        return match ($type) {
            User::class => 'Member',
            default => class_basename($type),
        };
    }

    protected function filteredQuery()
    {
        $query = AuditLog::query()->with(['actor', 'auditable']);

        if (trim($this->search) !== '') {
            $term = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($term) {
                $q->where('event', 'like', $term)
                    ->orWhere('ip_address', 'like', $term)
                    ->orWhere('auditable_type', 'like', $term)
                    ->orWhereHas('actor', fn ($a) => $a->where('username', 'like', $term)->orWhere('email', 'like', $term));
            });
        }

        if ($this->eventFilter !== '') {
            $query->where('event', $this->eventFilter);
        }

        if ($this->actorFilter !== '') {
            $query->where('actor_id', $this->actorFilter);
        }

        if ($this->typeFilter !== '') {
            $query->where('auditable_type', $this->typeFilter);
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
        $sortable = ['created_at', 'event'];
        $sortBy = in_array($this->sortBy, $sortable, true) ? $this->sortBy : 'created_at';

        return $this->filteredQuery()->orderBy($sortBy, $this->sortDir)->paginate($this->perPage);
    }

    protected function stats(): array
    {
        return [
            'total' => AuditLog::count(),
            'today' => AuditLog::whereDate('created_at', now()->toDateString())->count(),
            'this_week' => AuditLog::where('created_at', '>=', now()->subDays(7))->count(),
            'unique_actors' => AuditLog::whereNotNull('actor_id')->distinct('actor_id')->count('actor_id'),
        ];
    }

    protected function filterOptions(): array
    {
        return [
            'events' => AuditLog::select('event')->distinct()->orderBy('event')->pluck('event')->all(),
            'types' => AuditLog::select('auditable_type')->whereNotNull('auditable_type')->distinct()->orderBy('auditable_type')->pluck('auditable_type')->all(),
            'actors' => User::whereIn('id', AuditLog::whereNotNull('actor_id')->select('actor_id')->distinct())->orderBy('username')->get(['id', 'username']),
        ];
    }

    /**
     * Streams the currently filtered result set (capped at 5,000 rows) as
     * CSV. Exports everything matching the filters, not just the current
     * page.
     */
    public function export()
    {
        $rows = $this->filteredQuery()->orderBy('created_at', 'desc')->limit(5000)->get();

        $filename = 'audit-logs-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['When', 'Event', 'Actor', 'Subject Type', 'Subject ID', 'Old Values', 'New Values', 'Metadata', 'IP Address', 'User Agent']);

            foreach ($rows as $log) {
                fputcsv($out, [
                    optional($log->created_at)->toDateTimeString(),
                    $log->event,
                    $log->actor?->username ?? 'System',
                    self::auditableLabel($log->auditable_type),
                    $log->auditable_id,
                    json_encode($log->old_values),
                    json_encode($log->new_values),
                    json_encode($log->metadata),
                    $log->ip_address,
                    $log->user_agent,
                ]);
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function render()
    {
        return view('livewire.admin.audit-logs.audit-logs-manager', [
            'rows' => $this->rows(),
            'stats' => $this->stats(),
            'filterOptions' => $this->filterOptions(),
            'viewingLog' => $this->viewingId
                ? AuditLog::with(['actor', 'auditable'])->find($this->viewingId)
                : null,
        ]);
    }
}
