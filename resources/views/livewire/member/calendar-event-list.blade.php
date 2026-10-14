<div class="overflow-hidden rounded-[28px] border border-slate-200/80 bg-white/90 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85" wire:key="calendar-list-{{ $type }}">
    <div class="flex flex-col gap-3 border-b border-slate-200/80 p-5 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300">
                <x-dashboard-icon :name="$icon" class="h-5 w-5" />
            </span>
            <div>
                <h3 class="text-base font-bold text-slate-950 dark:text-white">{{ $heading }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $rows->total() }} total</p>
            </div>
        </div>
        <button type="button" wire:click="create" class="inline-flex shrink-0 items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Add
        </button>
    </div>

    {{-- Filters --}}
    <div class="grid gap-3 border-b border-slate-200/80 p-5 dark:border-slate-800 sm:grid-cols-12">
        <div class="sm:col-span-4">
            <input type="text" wire:model.live.debounce.400ms="search" placeholder="Search title, location…" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
        </div>
        <div class="sm:col-span-3">
            <input type="date" wire:model.live="from" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
        </div>
        <div class="sm:col-span-3">
            <input type="date" wire:model.live="to" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
        </div>
        @if ($type === 'task')
            <div class="sm:col-span-1">
                <select wire:model.live="status" class="w-full rounded-xl border border-slate-200 bg-white px-2 py-2 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                    <option value="">All</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Done</option>
                </select>
            </div>
            <div class="sm:col-span-1">
                <button type="button" wire:click="clearFilters" class="w-full rounded-xl border border-slate-200 px-2 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Clear</button>
            </div>
        @else
            <div class="sm:col-span-2">
                <button type="button" wire:click="clearFilters" class="w-full rounded-xl border border-slate-200 px-2 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Clear</button>
            </div>
        @endif
    </div>

    @php
        $columnCount = 5 + ($type === 'task' ? 1 : 0) + ($typeFields['prospect'] ? 1 : 0) + ($typeFields['phone'] ? 1 : 0) + ($typeFields['priority'] ? 1 : 0);
    @endphp
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
            <thead class="bg-slate-50/80 dark:bg-slate-800/70">
                <tr>
                    @if ($type === 'task')
                        <th class="w-10 px-4 py-3"></th>
                    @endif
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Title</th>
                    @if ($typeFields['prospect'])
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Contact</th>
                    @endif
                    @if ($typeFields['phone'])
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Phone</th>
                    @endif
                    @if ($typeFields['priority'])
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Priority</th>
                    @endif
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Date &amp; Time</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Calendar</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200/80 dark:divide-slate-800">
                @forelse ($rows as $event)
                    @php
                        $editable = in_array($event->calendar_id, $editableIds, true);
                        $isPast = ! $event->all_day && $event->start_at->isPast();
                        $isToday = $event->start_at->isToday();
                    @endphp
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-white/[0.03]" wire:key="row-{{ $type }}-{{ $event->id }}">
                        @if ($type === 'task')
                            <td class="px-4 py-3">
                                <button type="button" wire:click="toggleComplete({{ $event->id }})" @disabled(! $editable) title="{{ $event->isCompleted() ? 'Mark as pending' : 'Mark as done' }}" class="flex h-5 w-5 items-center justify-center rounded-full border-2 transition disabled:cursor-not-allowed disabled:opacity-50 {{ $event->isCompleted() ? 'border-emerald-500 bg-emerald-500 text-white' : 'border-slate-300 text-transparent hover:border-emerald-400 dark:border-slate-600' }}">
                                    <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-7 7a1 1 0 01-1.4 0l-3-3a1 1 0 111.4-1.4L9 11.6l6.3-6.3a1 1 0 011.4 0z" clip-rule="evenodd" /></svg>
                                </button>
                            </td>
                        @endif
                        <td class="px-4 py-3">
                            <p class="font-semibold text-slate-900 dark:text-white {{ $type === 'task' && $event->isCompleted() ? 'line-through text-slate-400 dark:text-slate-500' : '' }}">{{ $event->title }}</p>
                            @if ($event->location)
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $event->location }}</p>
                            @endif
                            @if ($event->meeting_link)
                                <a href="{{ $event->meeting_link }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 hover:underline dark:text-emerald-300">
                                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5 21 3m0 0h-5.25M21 3v5.25M19.5 13.5v5.25a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18.75V6.75A2.25 2.25 0 0 1 5.25 4.5h5.25" /></svg>
                                    Join meeting
                                </a>
                            @endif
                        </td>
                        @if ($typeFields['prospect'])
                            <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">
                                @if ($event->prospect)
                                    {{ trim($event->prospect->first_name.' '.$event->prospect->last_name) ?: 'Unnamed' }}
                                @else
                                    <span class="text-slate-400 dark:text-slate-500">&mdash;</span>
                                @endif
                            </td>
                        @endif
                        @if ($typeFields['phone'])
                            <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">{{ $event->phone_number ?: '—' }}</td>
                        @endif
                        @if ($typeFields['priority'])
                            <td class="px-4 py-3">
                                @if ($event->priority)
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-semibold capitalize {{ \App\Models\CalendarEvent::priorityBadgeClass($event->priority) }}">{{ $event->priority }}</span>
                                @else
                                    <span class="text-xs text-slate-400 dark:text-slate-500">&mdash;</span>
                                @endif
                            </td>
                        @endif
                        <td class="px-4 py-3 text-sm {{ $isPast ? 'text-rose-500' : 'text-slate-600 dark:text-slate-300' }}">
                            {{ $event->all_day ? $event->start_at->format('M d, Y') : $event->start_at->format('M d, Y g:ia') }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300">
                                <span class="h-2 w-2 shrink-0 rounded-full" style="background: {{ \App\Models\CalendarEvent::colorHex($event->displayColor()) }}"></span>
                                {{ $event->calendar?->name }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if ($type === 'task')
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $event->isCompleted() ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300' }}">
                                    {{ $event->isCompleted() ? 'Done' : 'Pending' }}
                                </span>
                            @elseif ($isToday)
                                <span class="inline-flex items-center rounded-full bg-sky-100 px-2.5 py-1 text-[11px] font-semibold text-sky-700 dark:bg-sky-500/15 dark:text-sky-300">Today</span>
                            @elseif ($isPast)
                                <span class="inline-flex items-center rounded-full bg-rose-100 px-2.5 py-1 text-[11px] font-semibold text-rose-700 dark:bg-rose-500/15 dark:text-rose-300">Past</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-[11px] font-semibold text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300">Upcoming</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1.5">
                                <button type="button" wire:click="edit({{ $event->id }})" title="{{ $editable ? 'Edit' : 'View' }}" class="inline-flex h-8 w-8 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" /></svg>
                                </button>
                                @if ($editable)
                                    <button type="button" wire:click="delete({{ $event->id }})" title="Delete" class="inline-flex h-8 w-8 items-center justify-center rounded-xl text-slate-500 transition hover:bg-rose-50 hover:text-rose-600 dark:text-slate-400 dark:hover:bg-rose-500/10 dark:hover:text-rose-300">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9M19.228 5.79c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397M4.772 5.79c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $columnCount }}" class="px-4 py-10 text-center text-sm text-slate-500 dark:text-slate-400">
                            Nothing here yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex flex-col gap-3 border-t border-slate-200/80 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800">
        <p class="text-xs text-slate-500 dark:text-slate-400">Showing {{ $rows->firstItem() ?? 0 }}&ndash;{{ $rows->lastItem() ?? 0 }} of {{ $rows->total() }}</p>
        <div>{{ $rows->links() }}</div>
    </div>
</div>
