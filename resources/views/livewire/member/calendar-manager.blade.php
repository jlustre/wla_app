<div class="space-y-4" x-data="calendarApp()" x-init="init($wire)" x-on:calendar-refresh.window="calendar && calendar.refetchEvents()">

    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-600 dark:text-emerald-300">Workspace</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950 dark:text-white sm:text-3xl">My Calendar</h1>
        </div>
    </div>

    @if (session('calendar_success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300">
            {{ session('calendar_success') }}
        </div>
    @endif

    <div class="grid gap-4 lg:grid-cols-[272px_1fr]">

        {{-- Left sidebar: create button, mini month picker, "My Calendars" --}}
        <div class="space-y-4">
            <button type="button" wire:click="openCreate" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Create Event
            </button>

            {{-- Mini month picker --}}
            <div class="rounded-[24px] border border-slate-200/80 bg-white/90 p-4 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
                <div class="mb-3 flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-900 dark:text-white" x-text="miniMonthLabel()"></p>
                    <div class="flex items-center gap-1">
                        <button type="button" @click="miniPrev()" class="rounded-full p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6" /></svg>
                        </button>
                        <button type="button" @click="miniNext()" class="rounded-full p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6" /></svg>
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-7 text-center text-[11px] font-semibold uppercase text-slate-400 dark:text-slate-500">
                    <span>S</span><span>M</span><span>T</span><span>W</span><span>T</span><span>F</span><span>S</span>
                </div>
                <div class="mt-1 space-y-0.5">
                    <template x-for="(week, wi) in miniWeeks" :key="wi">
                        <div class="grid grid-cols-7">
                            <template x-for="(day, di) in week" :key="di">
                                <button
                                    type="button"
                                    @click="jumpTo(day.date)"
                                    class="mx-auto flex h-7 w-7 items-center justify-center rounded-full text-xs transition"
                                    :class="{
                                        'text-slate-300 dark:text-slate-600': ! day.inMonth,
                                        'text-slate-700 dark:text-slate-300': day.inMonth && ! day.isToday,
                                        'bg-slate-950 font-bold text-white dark:bg-white dark:text-slate-950': day.isToday,
                                        'hover:bg-slate-100 dark:hover:bg-slate-800': ! day.isToday,
                                    }"
                                    x-text="day.date.getDate()"
                                ></button>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            {{-- My Calendars --}}
            <div class="rounded-[24px] border border-slate-200/80 bg-white/90 p-4 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">My Calendars</p>
                    <button type="button" wire:click="openCreateCalendar" title="Create new calendar" class="rounded-full p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    </button>
                </div>
                <div class="mt-3 space-y-0.5">
                    @foreach ($myCalendars as $calendar)
                        @php $hex = \App\Models\CalendarEvent::colorHex($calendar->color); @endphp
                        <div class="group flex items-center gap-2.5 rounded-xl px-1 py-1.5 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800/60" x-data="{ open: false }">
                            <label class="flex min-w-0 flex-1 cursor-pointer items-center gap-2.5">
                                <input
                                    type="checkbox"
                                    :checked="! hiddenCalendars.includes({{ $calendar->id }})"
                                    @change="toggleCalendar({{ $calendar->id }})"
                                    class="h-4 w-4 shrink-0 rounded border-slate-300 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800"
                                    style="accent-color: {{ $hex }}"
                                >
                                <span class="h-2.5 w-2.5 shrink-0 rounded-full" style="background: {{ $hex }}"></span>
                                <span class="min-w-0 flex-1 truncate">{{ $calendar->name }}</span>
                            </label>
                            <div class="relative shrink-0">
                                <button type="button" @click="open = ! open" class="rounded-full p-1 text-slate-400 opacity-0 transition hover:bg-slate-200 hover:text-slate-700 group-hover:opacity-100 dark:hover:bg-slate-700 dark:hover:text-slate-200" :class="{ 'opacity-100': open }">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 6.5a1.75 1.75 0 1 0 0-3.5 1.75 1.75 0 0 0 0 3.5Zm0 7a1.75 1.75 0 1 0 0-3.5 1.75 1.75 0 0 0 0 3.5Zm0 7a1.75 1.75 0 1 0 0-3.5 1.75 1.75 0 0 0 0 3.5Z"/></svg>
                                </button>
                                <div x-show="open" x-cloak @click.outside="open = false" x-transition class="absolute right-0 z-20 mt-1 w-40 overflow-hidden rounded-xl border border-slate-200 bg-white py-1 shadow-xl dark:border-slate-700 dark:bg-slate-800">
                                    <button type="button" wire:click="openEditCalendar({{ $calendar->id }})" @click="open = false" class="block w-full px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-700">Edit</button>
                                    <button type="button" wire:click="openShare({{ $calendar->id }})" @click="open = false" class="block w-full px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-700">Share</button>
                                    <button type="button" wire:click="confirmDeleteCalendar({{ $calendar->id }})" @click="open = false" class="block w-full px-3 py-2 text-left text-sm text-rose-600 hover:bg-rose-50 dark:text-rose-300 dark:hover:bg-rose-500/10">Delete</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('calendar') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            {{-- Other Calendars (shared with me) --}}
            @if ($sharedCalendars->isNotEmpty())
                <div class="rounded-[24px] border border-slate-200/80 bg-white/90 p-4 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Other Calendars</p>
                    <div class="mt-3 space-y-2">
                        @foreach ($sharedCalendars as $calendar)
                            @php
                                $hex = \App\Models\CalendarEvent::colorHex($calendar->color);
                                $permission = $calendar->shares->first()?->permission ?? 'view';
                            @endphp
                            <label class="flex cursor-pointer items-center gap-2.5 text-sm text-slate-700 dark:text-slate-200">
                                <input
                                    type="checkbox"
                                    :checked="! hiddenCalendars.includes({{ $calendar->id }})"
                                    @change="toggleCalendar({{ $calendar->id }})"
                                    class="h-4 w-4 shrink-0 rounded border-slate-300 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800"
                                    style="accent-color: {{ $hex }}"
                                >
                                <span class="h-2.5 w-2.5 shrink-0 rounded-full" style="background: {{ $hex }}"></span>
                                <span class="min-w-0 flex-1 truncate">{{ $calendar->name }}</span>
                                <span class="shrink-0 text-xs text-slate-400">{{ $calendar->user?->username }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Holidays (system calendars, read-only, visible to everyone) --}}
            @if ($holidayCalendars->isNotEmpty())
                <div class="rounded-[24px] border border-slate-200/80 bg-white/90 p-4 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Holidays</p>
                    <div class="mt-3 space-y-2">
                        @foreach ($holidayCalendars as $calendar)
                            @php $hex = \App\Models\CalendarEvent::colorHex($calendar->color); @endphp
                            <label class="flex cursor-pointer items-center gap-2.5 text-sm text-slate-700 dark:text-slate-200">
                                <input
                                    type="checkbox"
                                    :checked="! hiddenCalendars.includes({{ $calendar->id }})"
                                    @change="toggleCalendar({{ $calendar->id }})"
                                    class="h-4 w-4 shrink-0 rounded border-slate-300 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800"
                                    style="accent-color: {{ $hex }}"
                                >
                                <span class="h-2.5 w-2.5 shrink-0 rounded-full" style="background: {{ $hex }}"></span>
                                <span class="min-w-0 flex-1 truncate">{{ $calendar->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Main calendar --}}
        <div class="rounded-[28px] border border-slate-200/80 bg-white/90 p-4 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85 sm:p-5">
            <div class="flex flex-wrap items-center justify-between gap-3 pb-4">
                <div class="flex items-center gap-3">
                    <button type="button" @click="today()" class="rounded-xl border border-slate-200 px-3.5 py-1.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Today
                    </button>
                    <div class="flex items-center">
                        <button type="button" @click="prev()" class="rounded-full p-1.5 text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6" /></svg>
                        </button>
                        <button type="button" @click="next()" class="rounded-full p-1.5 text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6" /></svg>
                        </button>
                    </div>
                    <h2 class="text-lg font-bold text-slate-950 dark:text-white sm:text-xl" x-text="title"></h2>
                </div>

                <select x-model="viewType" @change="changeView(viewType)" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                    <option value="dayGridMonth">Month</option>
                    <option value="timeGridWeek">Week</option>
                    <option value="timeGridDay">Day</option>
                    <option value="listWeek">Schedule</option>
                </select>
            </div>

            <div wire:ignore x-ref="calendarEl" class="wla-calendar"></div>
        </div>
    </div>

    {{-- Create / Edit Event Modal --}}
    @if ($showEventModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm" wire:click.self="closeModal">
            <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-[32px] border border-slate-200/80 bg-white p-6 shadow-2xl dark:border-slate-800 dark:bg-slate-900 sm:p-8">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-300">{{ $editingId ? 'Edit Event' : 'New Event' }}</p>
                        <h2 class="mt-1 text-xl font-bold text-slate-950 dark:text-white">{{ $editingId ? 'Update event details' : 'Add an event to your calendar' }}</h2>
                    </div>
                    <button type="button" wire:click="closeModal" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="mt-6 space-y-4">
                    @if ($readOnly)
                        <p class="rounded-xl bg-sky-50 px-3 py-2 text-xs font-semibold text-sky-700 dark:bg-sky-500/10 dark:text-sky-300">You have view-only access to this calendar.</p>
                    @endif

                    <fieldset @if ($readOnly) disabled @endif class="space-y-4">
                    <div>
                        <input type="text" wire:model="title" placeholder="Add title" class="w-full border-b-2 border-slate-200 px-1 py-2 text-lg font-semibold text-slate-900 outline-none transition focus:border-emerald-500 disabled:opacity-60 dark:border-slate-700 dark:bg-transparent dark:text-white">
                        @error('title') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Type</label>
                            <select wire:model.live="type" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 disabled:opacity-60 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                @foreach ($typeOptions as $value => $meta)
                                    <option value="{{ $value }}">{{ $meta['label'] }}</option>
                                @endforeach
                            </select>
                            @error('type') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Calendar</label>
                            <select wire:model.live="calendarId" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 disabled:opacity-60 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                @foreach ($writableCalendars as $wc)
                                    <option value="{{ $wc->id }}">{{ $wc->name }}{{ $wc->user_id !== auth()->id() ? ' (shared)' : '' }}</option>
                                @endforeach
                            </select>
                            @error('calendarId') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Type-specific fields: calls & follow-ups link to a prospect,
                         calls get a phone number, follow-ups & tasks get a priority,
                         events get a meeting link. Nothing here is identical across types. --}}
                    @php $typeFields = \App\Models\CalendarEvent::fieldsForType($type); @endphp

                    @if ($typeFields['prospect'])
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Related Prospect</label>
                            @if ($prospectOptions->isEmpty())
                                <p class="rounded-xl bg-slate-50 px-3.5 py-2.5 text-sm text-slate-500 dark:bg-slate-800/70 dark:text-slate-400">
                                    You don't have any prospects yet. <x-spa-link :href="\App\Support\Nav::route('prospects.index')" class="font-semibold text-emerald-600 underline hover:no-underline dark:text-emerald-300">Add one</x-spa-link> to link it here.
                                </p>
                            @else
                                <select wire:model.live="prospectId" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                    <option value="">None</option>
                                    @foreach ($prospectOptions as $prospect)
                                        <option value="{{ $prospect->id }}">{{ trim($prospect->first_name.' '.$prospect->last_name) ?: 'Unnamed prospect' }}</option>
                                    @endforeach
                                </select>
                            @endif
                            @error('prospectId') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    @if ($typeFields['phone'] || $typeFields['priority'])
                        <div class="grid gap-3 sm:grid-cols-2">
                            @if ($typeFields['phone'])
                                <div>
                                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Phone Number</label>
                                    <input type="text" wire:model="phoneNumber" placeholder="+1 555-010-0100" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                    @error('phoneNumber') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                                </div>
                            @endif
                            @if ($typeFields['priority'])
                                <div>
                                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Priority</label>
                                    <select wire:model="priority" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                        @foreach ($priorityOptions as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if ($typeFields['meetingLink'])
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Meeting Link</label>
                            <input type="text" wire:model="meetingLink" placeholder="https://zoom.us/j/…" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                            @error('meetingLink') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-700 dark:text-slate-200">
                        <input type="checkbox" wire:model="all_day" class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800">
                        All day
                    </label>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Starts</label>
                            <div class="flex gap-2">
                                <input type="date" wire:model="start_date" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                @if (! $all_day)
                                    <input type="time" wire:model="start_time" class="w-28 shrink-0 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                @endif
                            </div>
                            @error('start_date') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Ends</label>
                            <div class="flex gap-2">
                                <input type="date" wire:model="end_date" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                @if (! $all_day)
                                    <input type="time" wire:model="end_time" class="w-28 shrink-0 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                @endif
                            </div>
                            @error('end_date') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            @error('end_time') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    @unless ($editingId)
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Repeat</label>
                                <select wire:model.live="repeat" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                    @foreach ($repeatOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($repeat !== 'none')
                                <div>
                                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Repeat until</label>
                                    <input type="date" wire:model="repeat_until" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                    <p class="mt-1 text-xs text-slate-400">Defaults to 1 year out if left blank.</p>
                                    @error('repeat_until') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                                </div>
                            @endif
                        </div>
                    @else
                        @if ($editingSeriesId)
                            <p class="rounded-xl bg-slate-50 px-3 py-2 text-xs text-slate-500 dark:bg-slate-800/70 dark:text-slate-400">This is part of a recurring series. Changes here apply to this occurrence only.</p>
                        @endif
                    @endunless

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Location</label>
                        <input type="text" wire:model="location" placeholder="Add location" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Description</label>
                        <textarea wire:model="description" rows="3" placeholder="Add description" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100"></textarea>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Notification</label>
                            <select wire:model="reminder_minutes" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                @foreach ($reminderOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div x-data="{ selectedColor: @entangle('color').live }">
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Color</label>
                            <div class="flex flex-wrap gap-2 pt-1">
                                @foreach ($colorPalette as $key => $meta)
                                    <button
                                        type="button"
                                        @click="selectedColor = '{{ $key }}'"
                                        title="{{ $meta['label'] }}"
                                        class="flex h-7 w-7 items-center justify-center rounded-full ring-2 ring-offset-2 transition dark:ring-offset-slate-900"
                                        :class="selectedColor === '{{ $key }}' ? 'ring-slate-950 dark:ring-white' : 'ring-transparent'"
                                        style="background: {{ $meta['hex'] }}"
                                    >
                                        <svg x-show="selectedColor === '{{ $key }}'" x-cloak class="h-3.5 w-3.5 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-7 7a1 1 0 01-1.4 0l-3-3a1 1 0 111.4-1.4L9 11.6l6.3-6.3a1 1 0 011.4 0z" clip-rule="evenodd" /></svg>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    </fieldset>

                    <div class="flex items-center justify-between gap-3 border-t border-slate-200/80 pt-5 dark:border-slate-800">
                        <div>
                            @if ($editingId && ! $readOnly)
                                <button type="button" wire:click="confirmDelete({{ $editingId }})" class="inline-flex items-center gap-1.5 rounded-xl px-3 py-2 text-sm font-semibold text-rose-600 transition hover:bg-rose-50 dark:text-rose-300 dark:hover:bg-rose-500/10">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9M19.228 5.79c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397M4.772 5.79c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                    Delete
                                </button>
                            @endif
                        </div>
                        <div class="flex items-center gap-3">
                            @if ($readOnly)
                                <button type="button" wire:click="closeModal" class="rounded-xl bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200">
                                    Close
                                </button>
                            @else
                                <button type="button" wire:click="closeModal" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                                    Cancel
                                </button>
                                <button type="submit" wire:loading.attr="disabled" wire:target="save" class="rounded-xl bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:opacity-60 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200">
                                    <span wire:loading.remove wire:target="save">Save</span>
                                    <span wire:loading wire:target="save">Saving…</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete confirm / recurrence-scope modal --}}
    @if ($deletingId)
        <div class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm" wire:click.self="cancelDelete">
            <div class="w-full max-w-sm rounded-[28px] border border-slate-200/80 bg-white p-6 shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 text-rose-500 dark:bg-rose-500/10">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
                </div>
                <h3 class="mt-4 text-lg font-bold text-slate-950 dark:text-white">Delete this event?</h3>

                @if ($deletingHasSeries)
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">This event repeats. What would you like to delete?</p>
                    <div class="mt-3 space-y-2">
                        <label class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-200">
                            <input type="radio" wire:model="deleteScope" value="this" class="text-rose-600 focus:ring-rose-500">
                            This event
                        </label>
                        <label class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-200">
                            <input type="radio" wire:model="deleteScope" value="following" class="text-rose-600 focus:ring-rose-500">
                            This and following events
                        </label>
                        <label class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-200">
                            <input type="radio" wire:model="deleteScope" value="all" class="text-rose-600 focus:ring-rose-500">
                            All events in the series
                        </label>
                    </div>
                @else
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">This action can't be undone.</p>
                @endif

                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" wire:click="cancelDelete" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                        Cancel
                    </button>
                    <button type="button" wire:click="delete" class="rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-500">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Create / Edit Calendar Modal --}}
    @if ($showCalendarModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm" wire:click.self="closeCalendarModal">
            <div class="w-full max-w-sm rounded-[28px] border border-slate-200/80 bg-white p-6 shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-300">{{ $editingCalendarId ? 'Edit Calendar' : 'New Calendar' }}</p>
                        <h2 class="mt-1 text-lg font-bold text-slate-950 dark:text-white">{{ $editingCalendarId ? 'Update calendar' : 'Create a calendar' }}</h2>
                    </div>
                    <button type="button" wire:click="closeCalendarModal" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveCalendar" class="mt-5 space-y-4">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Name</label>
                        <input type="text" wire:model="calendarName" placeholder="e.g. Personal, Work" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                        @error('calendarName') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div x-data="{ selectedColor: @entangle('calendarColor').live }">
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Color</label>
                        <div class="flex flex-wrap gap-2 pt-1">
                            @foreach ($colorPalette as $key => $meta)
                                <button
                                    type="button"
                                    @click="selectedColor = '{{ $key }}'"
                                    title="{{ $meta['label'] }}"
                                    class="flex h-7 w-7 items-center justify-center rounded-full ring-2 ring-offset-2 transition dark:ring-offset-slate-900"
                                    :class="selectedColor === '{{ $key }}' ? 'ring-slate-950 dark:ring-white' : 'ring-transparent'"
                                    style="background: {{ $meta['hex'] }}"
                                >
                                    <svg x-show="selectedColor === '{{ $key }}'" x-cloak class="h-3.5 w-3.5 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-7 7a1 1 0 01-1.4 0l-3-3a1 1 0 111.4-1.4L9 11.6l6.3-6.3a1 1 0 011.4 0z" clip-rule="evenodd" /></svg>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-3 border-t border-slate-200/80 pt-5 dark:border-slate-800">
                        <div>
                            @if ($editingCalendarId)
                                <button type="button" wire:click="confirmDeleteCalendar({{ $editingCalendarId }})" class="inline-flex items-center gap-1.5 rounded-xl px-3 py-2 text-sm font-semibold text-rose-600 transition hover:bg-rose-50 dark:text-rose-300 dark:hover:bg-rose-500/10">
                                    Delete
                                </button>
                            @endif
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button" wire:click="closeCalendarModal" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                                Cancel
                            </button>
                            <button type="submit" class="rounded-xl bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200">
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete Calendar confirm modal --}}
    @if ($deletingCalendarId)
        <div class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm" wire:click.self="cancelDeleteCalendar">
            <div class="w-full max-w-sm rounded-[28px] border border-slate-200/80 bg-white p-6 shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 text-rose-500 dark:bg-rose-500/10">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
                </div>
                <h3 class="mt-4 text-lg font-bold text-slate-950 dark:text-white">Delete this calendar?</h3>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    @if ($deletingCalendarEventCount > 0)
                        This will permanently delete {{ $deletingCalendarEventCount }} event{{ $deletingCalendarEventCount === 1 ? '' : 's' }} in it, and remove it from anyone it's shared with. This can't be undone.
                    @else
                        This will remove it from anyone it's shared with. This can't be undone.
                    @endif
                </p>
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" wire:click="cancelDeleteCalendar" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                        Cancel
                    </button>
                    <button type="button" wire:click="deleteCalendar" class="rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-500">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Share Calendar Modal --}}
    @if ($sharingCalendarId)
        @php $sharingCalendar = \App\Models\Calendar::find($sharingCalendarId); @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm" wire:click.self="closeShare">
            <div class="max-h-[85vh] w-full max-w-md overflow-y-auto rounded-[32px] border border-slate-200/80 bg-white p-6 shadow-2xl dark:border-slate-800 dark:bg-slate-900 sm:p-8">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-300">Share Calendar</p>
                        <h2 class="mt-1 text-lg font-bold text-slate-950 dark:text-white">{{ $sharingCalendar?->name }}</h2>
                    </div>
                    <button type="button" wire:click="closeShare" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                {{-- Add a person --}}
                <div class="mt-5">
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Share with a member</label>
                    <input type="text" wire:model.live.debounce.300ms="shareSearch" placeholder="Search by username or email…" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">

                    @if ($shareSearch !== '')
                        <div class="mt-2 max-h-48 overflow-y-auto rounded-xl border border-slate-200 dark:border-slate-700">
                            @forelse ($shareResults as $person)
                                <button type="button" wire:click="addShare({{ $person->id }})" class="flex w-full items-center justify-between px-4 py-2.5 text-left text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800">
                                    <span>{{ $person->username }}</span>
                                    <span class="text-xs text-slate-400">{{ $person->email }}</span>
                                </button>
                            @empty
                                <p class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">No matching members.</p>
                            @endforelse
                        </div>
                    @endif

                    <div class="mt-2 flex items-center gap-2">
                        <label class="text-xs font-semibold text-slate-500 dark:text-slate-400">Permission for new shares:</label>
                        <select wire:model="sharePermission" class="rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 outline-none focus:border-emerald-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                            @foreach ($permissionOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Current shares --}}
                <div class="mt-6">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">People with access</p>
                    <div class="mt-2 space-y-2">
                        @forelse ($activeShares as $share)
                            <div class="flex items-center justify-between gap-2 rounded-xl border border-slate-200 px-3 py-2.5 dark:border-slate-700">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $share->sharedWithUser?->username }}</p>
                                    <p class="truncate text-xs text-slate-400">{{ $share->sharedWithUser?->email }}</p>
                                </div>
                                <div class="flex shrink-0 items-center gap-1.5">
                                    <select wire:change="updateSharePermission({{ $share->id }}, $event.target.value)" class="rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 outline-none focus:border-emerald-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                                        @foreach ($permissionOptions as $value => $label)
                                            <option value="{{ $value }}" @selected($share->permission === $value)>{{ $value === 'edit' ? 'Edit' : 'View' }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" wire:click="removeShare({{ $share->id }})" title="Remove access" class="rounded-full p-1 text-slate-400 hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-500/10 dark:hover:text-rose-300">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-400">Not shared with anyone yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="mt-6 flex justify-end border-t border-slate-200/80 pt-5 dark:border-slate-800">
                    <button type="button" wire:click="closeShare" class="rounded-xl bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200">
                        Done
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
