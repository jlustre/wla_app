<?php

namespace App\Livewire\Member;

use App\Models\Calendar;
use App\Models\CalendarEvent;
use App\Models\CalendarShare;
use App\Models\Prospect;
use App\Models\User;
use App\Support\CalendarAccess;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class CalendarManager extends Component
{
    // Create / edit event modal
    public bool $showEventModal = false;

    public ?int $editingId = null;

    public ?string $editingSeriesId = null;

    public bool $readOnly = false;

    public string $title = '';

    public string $description = '';

    public string $location = '';

    public bool $all_day = false;

    public string $start_date = '';

    public string $start_time = '09:00';

    public string $end_date = '';

    public string $end_time = '10:00';

    public ?int $calendarId = null;

    public string $type = 'event';

    // Type-specific fields — which ones are shown is driven by
    // CalendarEvent::fieldsForType() in the Blade view.
    public ?int $prospectId = null;

    public string $phoneNumber = '';

    public string $priority = '';

    public string $meetingLink = '';

    public string $color = 'peacock';

    public string $reminder_minutes = '';

    public string $repeat = 'none';

    public string $repeat_until = '';

    // Delete event flow
    public ?int $deletingId = null;

    public bool $deletingHasSeries = false;

    public string $deleteScope = 'this';

    // Create / edit calendar modal
    public bool $showCalendarModal = false;

    public ?int $editingCalendarId = null;

    public string $calendarName = '';

    public string $calendarColor = 'peacock';

    // Delete calendar flow
    public ?int $deletingCalendarId = null;

    public int $deletingCalendarEventCount = 0;

    // Share calendar modal
    public ?int $sharingCalendarId = null;

    public string $shareSearch = '';

    public string $sharePermission = 'view';

    public array $colorPalette = [];

    public array $typeOptions = [];

    public array $priorityOptions = [];

    public array $reminderOptions = [
        '' => 'No reminder',
        '10' => '10 minutes before',
        '30' => '30 minutes before',
        '60' => '1 hour before',
        '1440' => '1 day before',
        '10080' => '1 week before',
    ];

    public array $repeatOptions = [
        'none' => 'Does not repeat',
        'daily' => 'Daily',
        'weekly' => 'Weekly',
        'monthly' => 'Monthly',
        'yearly' => 'Yearly',
    ];

    public array $permissionOptions = [
        'view' => 'See all event details',
        'edit' => 'Make changes to events',
    ];

    protected const MAX_OCCURRENCES = 260;

    public function mount(): void
    {
        $this->colorPalette = CalendarEvent::colorPalette();
        $this->typeOptions = CalendarEvent::typeOptions();
        $this->priorityOptions = CalendarEvent::priorityOptions();
        $this->ensureDefaultCalendar();
    }

    /**
     * The member's own prospects, for the "Related Prospect" picker shown
     * on calls and follow-ups.
     */
    protected function prospectOptions(): Collection
    {
        return Prospect::where('user_id', Auth::id())
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get(['id', 'first_name', 'last_name', 'phone']);
    }

    public function updatedProspectId(): void
    {
        if (! $this->prospectId || $this->phoneNumber !== '') {
            return;
        }

        $prospect = Prospect::where('user_id', Auth::id())->find($this->prospectId);

        if ($prospect && $prospect->phone) {
            $this->phoneNumber = $prospect->phone;
        }
    }

    protected function ensureDefaultCalendar(): Calendar
    {
        return Calendar::ensureDefaultFor(Auth::user());
    }

    // --- Permission helpers -------------------------------------------------

    protected function viewableCalendarIds(): array
    {
        return CalendarAccess::viewableCalendarIds(Auth::id());
    }

    protected function editableCalendarIds(): array
    {
        return CalendarAccess::editableCalendarIds(Auth::id());
    }

    // --- Sidebar calendar lists (consumed by the Blade view) ----------------

    public function myCalendars(): Collection
    {
        return Calendar::where('user_id', Auth::id())
            ->withCount('events')
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->get();
    }

    public function sharedCalendars(): Collection
    {
        return Calendar::whereIn('id', CalendarShare::where('shared_with_user_id', Auth::id())->pluck('calendar_id'))
            ->with(['user', 'shares' => fn ($q) => $q->where('shared_with_user_id', Auth::id())])
            ->orderBy('name')
            ->get();
    }

    public function writableCalendarsForForm(): Collection
    {
        return Calendar::whereIn('id', $this->editableCalendarIds())
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->get();
    }

    /**
     * System "Holidays in …" calendars — read-only, visible to every member.
     */
    public function holidayCalendars(): Collection
    {
        return Calendar::holidays()->orderBy('name')->get();
    }

    // --- Event modal ----------------------------------------------------

    public function openCreate(?string $startIso = null, ?string $endIso = null, bool $allDay = false, ?string $type = null): void
    {
        $this->resetForm();

        $start = $startIso ? Carbon::parse($startIso) : now();
        $end = $endIso ? Carbon::parse($endIso) : (clone $start)->addHour();

        $this->all_day = $allDay;
        $this->start_date = $start->format('Y-m-d');
        $this->end_date = $allDay ? (clone $end)->subDay()->format('Y-m-d') : $end->format('Y-m-d');
        $this->start_time = $allDay ? '09:00' : $start->format('H:i');
        $this->end_time = $allDay ? '10:00' : $end->format('H:i');

        if ($this->end_date < $this->start_date) {
            $this->end_date = $this->start_date;
        }

        if ($type && array_key_exists($type, CalendarEvent::typeOptions())) {
            $this->type = $type;
        }

        $this->readOnly = false;
        $this->showEventModal = true;
    }

    /**
     * Opens the create modal pre-filled for a given type — used by the
     * "+ Add" button on each listing table below the calendar.
     */
    #[On('calendar-create-requested')]
    public function handleCreateRequest(string $type): void
    {
        $this->openCreate(null, null, false, $type);
    }

    #[On('calendar-edit-requested')]
    public function handleEditRequest(int $id): void
    {
        $this->openEdit($id);
    }

    #[On('calendar-delete-requested')]
    public function handleDeleteRequest(int $id): void
    {
        $this->confirmDelete($id);
    }

    public function openEdit(int $id): void
    {
        $event = CalendarEvent::whereIn('calendar_id', $this->viewableCalendarIds())->findOrFail($id);

        $this->editingId = $event->id;
        $this->editingSeriesId = $event->series_id;
        $this->title = $event->title;
        $this->description = $event->description ?? '';
        $this->location = $event->location ?? '';
        $this->all_day = $event->all_day;
        $this->start_date = $event->start_at->format('Y-m-d');
        $this->start_time = $event->start_at->format('H:i');
        $this->end_date = $event->end_at ? $event->end_at->format('Y-m-d') : $event->start_at->format('Y-m-d');
        $this->end_time = $event->end_at ? $event->end_at->format('H:i') : $event->start_at->addHour()->format('H:i');
        $this->calendarId = $event->calendar_id;
        $this->type = $event->type ?? 'event';
        $this->prospectId = $event->prospect_id;
        $this->phoneNumber = $event->phone_number ?? '';
        $this->priority = $event->priority ?? '';
        $this->meetingLink = $event->meeting_link ?? '';
        $this->color = $event->displayColor();
        $this->reminder_minutes = $event->reminder_minutes ? (string) $event->reminder_minutes : '';
        $this->repeat = 'none';
        $this->repeat_until = '';
        $this->readOnly = ! in_array($event->calendar_id, $this->editableCalendarIds(), true);
        $this->resetErrorBag();
        $this->showEventModal = true;
    }

    public function closeModal(): void
    {
        $this->showEventModal = false;
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->reset([
            'editingId', 'editingSeriesId', 'title', 'description', 'location',
            'all_day', 'reminder_minutes', 'repeat', 'repeat_until', 'readOnly', 'type',
            'prospectId', 'phoneNumber', 'priority', 'meetingLink',
        ]);
        $this->start_date = now()->format('Y-m-d');
        $this->end_date = now()->format('Y-m-d');
        $this->start_time = '09:00';
        $this->end_time = '10:00';
        $this->repeat = 'none';

        $default = $this->ensureDefaultCalendar();
        $this->calendarId = $default->id;
        $this->color = $default->color;

        $this->resetErrorBag();
    }

    public function updatedCalendarId(): void
    {
        $calendar = Calendar::find($this->calendarId);

        if ($calendar) {
            $this->color = $calendar->color;
        }
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'location' => 'nullable|string|max:255',
            'calendarId' => 'required|integer|in:'.implode(',', $this->editableCalendarIds() ?: [0]),
            'type' => 'required|in:'.implode(',', array_keys(CalendarEvent::typeOptions())),
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required_if:all_day,false',
            'end_time' => 'required_if:all_day,false',
            'color' => 'required|string|in:'.implode(',', array_keys(CalendarEvent::colorPalette())),
            'reminder_minutes' => 'nullable|integer',
            'repeat' => 'required|in:none,daily,weekly,monthly,yearly',
            'repeat_until' => 'nullable|date|after_or_equal:start_date',
            'prospectId' => 'nullable|integer|exists:prospects,id',
            'phoneNumber' => 'nullable|string|max:50',
            'priority' => 'nullable|in:low,medium,high',
            'meetingLink' => 'nullable|url|max:255',
        ];
    }

    public function save(): void
    {
        if ($this->readOnly) {
            return;
        }

        $this->validate();

        if ($this->prospectId && ! Prospect::where('user_id', Auth::id())->where('id', $this->prospectId)->exists()) {
            $this->addError('prospectId', 'That prospect could not be found.');

            return;
        }

        [$startAt, $endAt] = $this->buildRange();

        if ($endAt->lt($startAt)) {
            $this->addError('end_time', 'End must be after start.');

            return;
        }

        // Only persist the fields that actually apply to this type, so
        // switching types mid-edit doesn't leave stale data behind.
        $fields = CalendarEvent::fieldsForType($this->type);

        $data = [
            'user_id' => Auth::id(),
            'calendar_id' => $this->calendarId,
            'type' => $this->type,
            'title' => $this->title,
            'description' => $this->description ?: null,
            'location' => $this->location ?: null,
            'start_at' => $startAt,
            'end_at' => $endAt,
            'all_day' => $this->all_day,
            'color' => $this->color,
            'reminder_minutes' => $this->reminder_minutes !== '' ? (int) $this->reminder_minutes : null,
            'prospect_id' => $fields['prospect'] ? $this->prospectId : null,
            'phone_number' => $fields['phone'] ? ($this->phoneNumber ?: null) : null,
            'priority' => $fields['priority'] ? ($this->priority ?: null) : null,
            'meeting_link' => $fields['meetingLink'] ? ($this->meetingLink ?: null) : null,
        ];

        if ($this->editingId) {
            $event = CalendarEvent::whereIn('calendar_id', $this->editableCalendarIds())->findOrFail($this->editingId);
            $event->update($data);
            session()->flash('calendar_success', 'Event updated.');
        } elseif ($this->repeat !== 'none') {
            $this->createSeries($data, $startAt, $endAt);
            session()->flash('calendar_success', 'Recurring event created.');
        } else {
            CalendarEvent::create($data);
            session()->flash('calendar_success', 'Event created.');
        }

        $this->showEventModal = false;
        $this->resetForm();
        $this->dispatch('calendar-refresh');
    }

    protected function buildRange(): array
    {
        if ($this->all_day) {
            $start = Carbon::parse($this->start_date)->startOfDay();
            $end = Carbon::parse($this->end_date)->endOfDay();
        } else {
            $start = Carbon::parse($this->start_date.' '.$this->start_time);
            $end = Carbon::parse($this->end_date.' '.$this->end_time);
        }

        return [$start, $end];
    }

    protected function createSeries(array $data, Carbon $startAt, Carbon $endAt): void
    {
        $seriesId = (string) Str::uuid();
        $duration = $startAt->diffInSeconds($endAt);
        $until = $this->repeat_until !== '' ? Carbon::parse($this->repeat_until)->endOfDay() : (clone $startAt)->addYear();

        $cursorStart = $startAt->copy();
        $rows = [];
        $count = 0;

        while ($cursorStart->lte($until) && $count < self::MAX_OCCURRENCES) {
            $cursorEnd = (clone $cursorStart)->addSeconds($duration);

            $rows[] = array_merge($data, [
                'start_at' => $cursorStart->copy(),
                'end_at' => $cursorEnd,
                'series_id' => $seriesId,
                'recurrence_frequency' => $this->repeat,
                'recurrence_until' => $until->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $count++;

            $cursorStart = match ($this->repeat) {
                'daily' => $cursorStart->addDay(),
                'weekly' => $cursorStart->addWeek(),
                'monthly' => $cursorStart->addMonthNoOverflow(),
                'yearly' => $cursorStart->addYear(),
                default => $until->copy()->addDay(),
            };
        }

        foreach (array_chunk($rows, 100) as $chunk) {
            CalendarEvent::insert($chunk);
        }
    }

    public function confirmDelete(int $id): void
    {
        $event = CalendarEvent::whereIn('calendar_id', $this->editableCalendarIds())->findOrFail($id);
        $this->deletingId = $event->id;
        $this->deletingHasSeries = (bool) $event->series_id;
        $this->deleteScope = 'this';
    }

    public function cancelDelete(): void
    {
        $this->deletingId = null;
    }

    public function delete(): void
    {
        if (! $this->deletingId) {
            return;
        }

        $event = CalendarEvent::whereIn('calendar_id', $this->editableCalendarIds())->find($this->deletingId);

        if (! $event) {
            $this->deletingId = null;

            return;
        }

        if ($event->series_id && $this->deleteScope !== 'this') {
            $query = CalendarEvent::where('calendar_id', $event->calendar_id)->where('series_id', $event->series_id);

            if ($this->deleteScope === 'following') {
                $query->where('start_at', '>=', $event->start_at);
            }

            $query->delete();
        } else {
            $event->delete();
        }

        session()->flash('calendar_success', 'Event deleted.');
        $this->deletingId = null;
        $this->showEventModal = false;
        $this->dispatch('calendar-refresh');
    }

    /**
     * Called from JS (FullCalendar's `events` function source) via
     * $wire.call('getEvents', startIso, endIso).
     */
    public function getEvents(string $start, string $end): array
    {
        $editableIds = $this->editableCalendarIds();

        // Normalize to the same "Y-m-d H:i:s" format the column is stored
        // in before comparing. FullCalendar sends ISO 8601 (with a literal
        // "T" separator); left as-is, a plain string comparison against
        // space-separated stored values can sort incorrectly on drivers
        // without native datetime typing (e.g. SQLite), even though it
        // happens to work on MySQL's more lenient implicit parsing.
        $startAt = Carbon::parse($start)->format('Y-m-d H:i:s');
        $endAt = Carbon::parse($end)->format('Y-m-d H:i:s');

        $events = CalendarEvent::whereIn('calendar_id', $this->viewableCalendarIds())
            ->with('calendar')
            ->where('start_at', '<', $endAt)
            ->where(function ($q) use ($startAt) {
                $q->where('end_at', '>=', $startAt)->orWhereNull('end_at');
            })
            ->orderBy('start_at')
            ->get();

        return $events->map(function (CalendarEvent $event) use ($editableIds) {
            $hex = CalendarEvent::colorHex($event->displayColor());
            $editable = in_array($event->calendar_id, $editableIds, true);

            return [
                'id' => (string) $event->id,
                'title' => $event->title,
                'start' => $event->all_day
                    ? $event->start_at->format('Y-m-d')
                    : $event->start_at->toIso8601String(),
                'end' => $event->all_day
                    ? $event->end_at->copy()->addDay()->format('Y-m-d')
                    : optional($event->end_at)->toIso8601String(),
                'allDay' => $event->all_day,
                'backgroundColor' => $hex,
                'borderColor' => $hex,
                'editable' => $editable,
                'startEditable' => $editable,
                'durationEditable' => $editable,
                'extendedProps' => [
                    'calendarId' => $event->calendar_id,
                    'calendarName' => $event->calendar?->name,
                    'location' => $event->location,
                    'description' => $event->description,
                    'isSeries' => (bool) $event->series_id,
                ],
            ];
        })->all();
    }

    /**
     * Called from JS after a drag (eventDrop) or resize (eventResize).
     */
    public function updateEventTimes(int $id, string $start, ?string $end, bool $allDay): void
    {
        $event = CalendarEvent::whereIn('calendar_id', $this->editableCalendarIds())->find($id);

        if (! $event) {
            return;
        }

        $startAt = Carbon::parse($start);
        $endAt = $end ? Carbon::parse($end) : (clone $startAt)->addHour();

        if ($allDay) {
            $endAt = $endAt->copy()->subDay()->endOfDay();
            $startAt = $startAt->copy()->startOfDay();
        }

        $event->update([
            'start_at' => $startAt,
            'end_at' => $endAt,
            'all_day' => $allDay,
        ]);
    }

    // --- Calendar management (create / rename / delete) ---------------------

    public function openCreateCalendar(): void
    {
        $this->editingCalendarId = null;
        $this->calendarName = '';
        $this->calendarColor = 'peacock';
        $this->resetErrorBag();
        $this->showCalendarModal = true;
    }

    public function openEditCalendar(int $id): void
    {
        $calendar = Calendar::where('user_id', Auth::id())->findOrFail($id);
        $this->editingCalendarId = $calendar->id;
        $this->calendarName = $calendar->name;
        $this->calendarColor = $calendar->color;
        $this->resetErrorBag();
        $this->showCalendarModal = true;
    }

    public function closeCalendarModal(): void
    {
        $this->showCalendarModal = false;
    }

    public function saveCalendar(): void
    {
        $this->validate([
            'calendarName' => 'required|string|max:255',
            'calendarColor' => 'required|string|in:'.implode(',', array_keys(CalendarEvent::colorPalette())),
        ]);

        $userId = Auth::id();

        if ($this->editingCalendarId) {
            Calendar::where('user_id', $userId)->findOrFail($this->editingCalendarId)
                ->update(['name' => $this->calendarName, 'color' => $this->calendarColor]);
            session()->flash('calendar_success', 'Calendar updated.');
        } else {
            Calendar::create([
                'user_id' => $userId,
                'name' => $this->calendarName,
                'color' => $this->calendarColor,
                'is_default' => false,
            ]);
            session()->flash('calendar_success', 'Calendar created.');
        }

        $this->showCalendarModal = false;
        $this->dispatch('calendar-refresh');
    }

    public function confirmDeleteCalendar(int $id): void
    {
        $userId = Auth::id();

        if (Calendar::where('user_id', $userId)->count() <= 1) {
            $this->addError('calendar', 'You must keep at least one calendar — create another before deleting this one.');

            return;
        }

        $calendar = Calendar::where('user_id', $userId)->withCount('events')->findOrFail($id);
        $this->deletingCalendarId = $calendar->id;
        $this->deletingCalendarEventCount = $calendar->events_count;
        $this->showCalendarModal = false;
    }

    public function cancelDeleteCalendar(): void
    {
        $this->deletingCalendarId = null;
    }

    public function deleteCalendar(): void
    {
        if (! $this->deletingCalendarId) {
            return;
        }

        $calendar = Calendar::where('user_id', Auth::id())->find($this->deletingCalendarId);

        if ($calendar) {
            $wasDefault = $calendar->is_default;
            $calendar->delete(); // cascades its events and shares

            if ($wasDefault) {
                $this->ensureDefaultCalendar();
            }

            session()->flash('calendar_success', 'Calendar deleted.');
        }

        $this->deletingCalendarId = null;
        $this->dispatch('calendar-refresh');
    }

    // --- Sharing --------------------------------------------------------

    public function openShare(int $calendarId): void
    {
        $this->sharingCalendarId = Calendar::where('user_id', Auth::id())->findOrFail($calendarId)->id;
        $this->shareSearch = '';
        $this->sharePermission = 'view';
        $this->resetErrorBag();
    }

    public function closeShare(): void
    {
        $this->sharingCalendarId = null;
        $this->shareSearch = '';
    }

    public function shareSearchResults(): Collection
    {
        if (! $this->sharingCalendarId || trim($this->shareSearch) === '') {
            return collect();
        }

        $term = '%'.trim($this->shareSearch).'%';
        $alreadyShared = CalendarShare::where('calendar_id', $this->sharingCalendarId)->pluck('shared_with_user_id');

        return User::where('id', '!=', Auth::id())
            ->whereNotIn('id', $alreadyShared)
            ->where(function ($q) use ($term) {
                $q->where('username', 'like', $term)->orWhere('email', 'like', $term);
            })
            ->orderBy('username')
            ->limit(8)
            ->get(['id', 'username', 'email']);
    }

    public function currentShares(): Collection
    {
        if (! $this->sharingCalendarId) {
            return collect();
        }

        return CalendarShare::where('calendar_id', $this->sharingCalendarId)
            ->with('sharedWithUser')
            ->get();
    }

    public function addShare(int $userId): void
    {
        if (! $this->sharingCalendarId) {
            return;
        }

        $calendar = Calendar::where('user_id', Auth::id())->find($this->sharingCalendarId);

        if (! $calendar) {
            return;
        }

        CalendarShare::updateOrCreate(
            ['calendar_id' => $calendar->id, 'shared_with_user_id' => $userId],
            ['permission' => in_array($this->sharePermission, ['view', 'edit'], true) ? $this->sharePermission : 'view']
        );

        $this->shareSearch = '';
    }

    public function updateSharePermission(int $shareId, string $permission): void
    {
        if (! in_array($permission, ['view', 'edit'], true)) {
            return;
        }

        CalendarShare::where('id', $shareId)
            ->whereHas('calendar', fn ($q) => $q->where('user_id', Auth::id()))
            ->update(['permission' => $permission]);
    }

    public function removeShare(int $shareId): void
    {
        CalendarShare::where('id', $shareId)
            ->whereHas('calendar', fn ($q) => $q->where('user_id', Auth::id()))
            ->delete();
    }

    public function render()
    {
        return view('livewire.member.calendar-manager', [
            'myCalendars' => $this->myCalendars(),
            'sharedCalendars' => $this->sharedCalendars(),
            'holidayCalendars' => $this->holidayCalendars(),
            'writableCalendars' => $this->writableCalendarsForForm(),
            'shareResults' => $this->shareSearchResults(),
            'activeShares' => $this->currentShares(),
            'prospectOptions' => $this->prospectOptions(),
        ]);
    }
}
