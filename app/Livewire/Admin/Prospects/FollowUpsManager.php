<?php

namespace App\Livewire\Admin\Prospects;

use App\Models\Prospect;
use App\Models\Timeline;
use App\Models\User;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Admin worklist for prospect follow-ups: every prospect with (or without)
 * a next_follow_up date, across every member, sorted most-urgent first.
 * Lets an admin log that a follow-up happened (which writes a Timeline
 * entry, same source of truth as the member-side Activity Log), quickly
 * reschedule or clear the due date, or just view the prospect's history.
 */
class FollowUpsManager extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $ownerFilter = '';

    #[Url(history: true)]
    public string $hotnessFilter = '';

    #[Url(history: true)]
    public string $statusFilter = '';

    public int $perPage = 10;

    // View modal
    public ?int $viewingProspectId = null;

    // Log-activity modal
    public ?int $loggingProspectId = null;

    public string $logAction = 'Phone Call';

    public string $logNotes = '';

    public string $logDate = '';

    public string $logNextFollowUp = '';

    // Reschedule modal
    public ?int $reschedulingProspectId = null;

    public string $rescheduleDate = '';

    public array $hotnessOptions = ['Hot', 'Warm', 'Cold'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingOwnerFilter(): void
    {
        $this->resetPage();
    }

    public function updatingHotnessFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'ownerFilter', 'hotnessFilter', 'statusFilter']);
        $this->resetPage();
    }

    public function viewDetails(int $id): void
    {
        $this->viewingProspectId = Prospect::findOrFail($id)->id;
    }

    public function closeDetails(): void
    {
        $this->viewingProspectId = null;
    }

    public function openLog(int $id): void
    {
        Prospect::findOrFail($id);

        $this->loggingProspectId = $id;
        $this->logAction = 'Phone Call';
        $this->logNotes = '';
        $this->logDate = now()->format('Y-m-d\TH:i');
        $this->logNextFollowUp = '';
        $this->resetErrorBag();
    }

    public function cancelLog(): void
    {
        $this->reset(['loggingProspectId', 'logAction', 'logNotes', 'logDate', 'logNextFollowUp']);
        $this->resetErrorBag();
    }

    protected function logRules(): array
    {
        return [
            'logAction' => 'required|string|max:50',
            'logDate' => 'required|date',
            'logNotes' => 'nullable|string|max:2000',
            'logNextFollowUp' => 'nullable|date',
        ];
    }

    public function saveLog(): void
    {
        $this->validate($this->logRules());

        $prospect = Prospect::findOrFail($this->loggingProspectId);

        Timeline::create([
            'prospect_id' => $prospect->id,
            'action' => $this->logAction,
            'notes' => $this->logNotes !== '' ? $this->logNotes : null,
            'action_at' => $this->logDate,
            'next_follow_up_dt' => $this->logNextFollowUp !== '' ? $this->logNextFollowUp : null,
        ]);

        // Logging a follow-up either reschedules it (a new date was given)
        // or completes it (no new date clears the due reminder).
        $prospect->update(['next_follow_up' => $this->logNextFollowUp !== '' ? $this->logNextFollowUp : null]);

        session()->flash('success', 'Follow-up logged.');
        $this->cancelLog();
    }

    public function openReschedule(int $id): void
    {
        $prospect = Prospect::findOrFail($id);

        $this->reschedulingProspectId = $prospect->id;
        $this->rescheduleDate = optional($prospect->next_follow_up)->format('Y-m-d\TH:i') ?? now()->addDay()->format('Y-m-d\TH:i');
        $this->resetErrorBag();
    }

    public function cancelReschedule(): void
    {
        $this->reset(['reschedulingProspectId', 'rescheduleDate']);
        $this->resetErrorBag();
    }

    public function saveReschedule(): void
    {
        $this->validate(['rescheduleDate' => 'required|date']);

        Prospect::findOrFail($this->reschedulingProspectId)->update(['next_follow_up' => $this->rescheduleDate]);

        session()->flash('success', 'Follow-up rescheduled.');
        $this->cancelReschedule();
    }

    public function clearFollowUp(int $id): void
    {
        Prospect::where('id', $id)->update(['next_follow_up' => null]);

        session()->flash('success', 'Follow-up cleared.');
    }

    protected function baseQuery()
    {
        $query = Prospect::query()->with(['user', 'latestActivity']);

        if (trim($this->search) !== '') {
            $term = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($term) {
                $q->where('first_name', 'like', $term)
                    ->orWhere('last_name', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('phone', 'like', $term);
            });
        }

        if ($this->ownerFilter !== '') {
            $query->where('user_id', $this->ownerFilter);
        }

        if ($this->hotnessFilter !== '') {
            $query->where('hotness', $this->hotnessFilter);
        }

        return $query;
    }

    protected function applyStatus($query)
    {
        $now = now();

        return match ($this->statusFilter) {
            'overdue' => $query->whereNotNull('next_follow_up')->where('next_follow_up', '<', $now),
            'today' => $query->whereNotNull('next_follow_up')->whereDate('next_follow_up', $now->toDateString()),
            'week' => $query->whereNotNull('next_follow_up')->whereBetween('next_follow_up', [$now, $now->copy()->addDays(7)]),
            'upcoming' => $query->whereNotNull('next_follow_up')->where('next_follow_up', '>', $now->copy()->addDays(7)),
            'none' => $query->whereNull('next_follow_up'),
            default => $query,
        };
    }

    protected function rows()
    {
        $query = $this->applyStatus($this->baseQuery());

        return $query
            ->orderByRaw('next_follow_up IS NULL')
            ->orderBy('next_follow_up', 'asc')
            ->paginate($this->perPage);
    }

    protected function stats(): array
    {
        $now = now();
        $base = $this->baseQuery();

        return [
            'overdue' => (clone $base)->whereNotNull('next_follow_up')->where('next_follow_up', '<', $now)->count(),
            'today' => (clone $base)->whereNotNull('next_follow_up')->whereDate('next_follow_up', $now->toDateString())->count(),
            'due_this_week' => (clone $base)->whereNotNull('next_follow_up')->whereBetween('next_follow_up', [$now, $now->copy()->addDays(7)])->count(),
            'none' => (clone $base)->whereNull('next_follow_up')->count(),
        ];
    }

    protected function filterOptions(): array
    {
        return [
            'owners' => User::whereIn('id', Prospect::select('user_id')->distinct())->orderBy('username')->get(['id', 'username']),
        ];
    }

    public function render()
    {
        return view('livewire.admin.prospects.follow-ups-manager', [
            'rows' => $this->rows(),
            'stats' => $this->stats(),
            'filterOptions' => $this->filterOptions(),
            'activityOptions' => Timeline::actionOptions(),
            'viewingProspect' => $this->viewingProspectId
                ? Prospect::with('user')->find($this->viewingProspectId)
                : null,
            'viewingActivities' => $this->viewingProspectId
                ? Timeline::where('prospect_id', $this->viewingProspectId)->orderByDesc('action_at')->orderByDesc('id')->get()
                : collect(),
            'loggingProspect' => $this->loggingProspectId
                ? Prospect::find($this->loggingProspectId)
                : null,
            'reschedulingProspect' => $this->reschedulingProspectId
                ? Prospect::find($this->reschedulingProspectId)
                : null,
        ]);
    }
}
