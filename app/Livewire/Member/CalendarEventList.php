<?php

namespace App\Livewire\Member;

use App\Models\CalendarEvent;
use App\Support\CalendarAccess;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * One filterable, paginated table of calendar events for a single type
 * ('call' | 'follow_up' | 'task' | 'event'). Rendered four times below the
 * calendar grid (resources/views/member/calendar.blade.php) — one per
 * type — each instance is fully independent (its own filters, its own page
 * query-string key via $type as the pagination page name).
 *
 * Edit/Delete/Create don't duplicate the modal already built into
 * App\Livewire\Member\CalendarManager — they dispatch browser events that
 * component listens for (#[On('calendar-edit-requested')] etc.), so there's
 * one single source of truth for the event form regardless of whether it
 * was opened from the grid or from one of these tables. The same
 * "calendar-refresh" event CalendarManager fires after any change is what
 * tells this table to re-query.
 */
class CalendarEventList extends Component
{
    use WithPagination;

    public string $type;

    public string $heading;

    public string $icon;

    public string $search = '';

    public string $from = '';

    public string $to = '';

    public string $status = '';

    public int $perPage = 5;

    public function mount(string $type, string $heading, string $icon): void
    {
        $this->type = $type;
        $this->heading = $heading;
        $this->icon = $icon;
    }

    public function updatingSearch(): void
    {
        $this->resetPage($this->type);
    }

    public function updatingFrom(): void
    {
        $this->resetPage($this->type);
    }

    public function updatingTo(): void
    {
        $this->resetPage($this->type);
    }

    public function updatingStatus(): void
    {
        $this->resetPage($this->type);
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'from', 'to', 'status']);
        $this->resetPage($this->type);
    }

    public function edit(int $id): void
    {
        $this->dispatch('calendar-edit-requested', id: $id);
    }

    public function delete(int $id): void
    {
        $this->dispatch('calendar-delete-requested', id: $id);
    }

    public function create(): void
    {
        $this->dispatch('calendar-create-requested', type: $this->type);
    }

    public function toggleComplete(int $id): void
    {
        $event = CalendarEvent::whereIn('calendar_id', CalendarAccess::editableCalendarIds(Auth::id()))->find($id);

        if ($event) {
            $event->update(['completed_at' => $event->isCompleted() ? null : now()]);
        }
    }

    #[On('calendar-refresh')]
    public function refreshList(): void
    {
        // No-op: having this listener is what makes Livewire re-render
        // (and therefore re-query) this table after CalendarManager
        // dispatches "calendar-refresh" from save()/delete().
    }

    protected function rows()
    {
        $query = CalendarEvent::whereIn('calendar_id', CalendarAccess::viewableCalendarIds(Auth::id()))
            ->ofType($this->type)
            ->with(['calendar', 'prospect']);

        if (trim($this->search) !== '') {
            $term = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('location', 'like', $term)
                    ->orWhere('description', 'like', $term);
            });
        }

        if ($this->from !== '') {
            $query->whereDate('start_at', '>=', $this->from);
        }

        if ($this->to !== '') {
            $query->whereDate('start_at', '<=', $this->to);
        }

        if ($this->type === 'task' && $this->status !== '') {
            $this->status === 'completed'
                ? $query->whereNotNull('completed_at')
                : $query->whereNull('completed_at');
        }

        return $query->orderBy('start_at')->paginate($this->perPage, ['*'], $this->type);
    }

    public function render()
    {
        $editableIds = CalendarAccess::editableCalendarIds(Auth::id());

        return view('livewire.member.calendar-event-list', [
            'rows' => $this->rows(),
            'editableIds' => $editableIds,
            'typeFields' => CalendarEvent::fieldsForType($this->type),
        ]);
    }
}
