<div class="w-full space-y-6" wire:key="admin-follow-ups-manager-root">

    {{-- Header --}}
    <div>
        <h1 class="text-3xl font-extrabold text-teal-800">Follow-ups</h1>
        <p class="mt-1 text-sm text-slate-500">Every prospect's next follow-up date across every member, most urgent first.</p>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-xl bg-green-100 text-green-800 font-semibold">{{ session('success') }}</div>
    @endif

    {{-- Stats --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <button type="button" wire:click="$set('statusFilter', 'overdue')" class="rounded-2xl bg-white p-5 text-left shadow ring-1 ring-black/5 transition hover:ring-rose-300 {{ $statusFilter === 'overdue' ? 'ring-2 ring-rose-500' : '' }}">
            <p class="text-xs font-bold uppercase tracking-wider text-rose-500">Overdue</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['overdue']) }}</p>
        </button>
        <button type="button" wire:click="$set('statusFilter', 'today')" class="rounded-2xl bg-white p-5 text-left shadow ring-1 ring-black/5 transition hover:ring-amber-300 {{ $statusFilter === 'today' ? 'ring-2 ring-amber-500' : '' }}">
            <p class="text-xs font-bold uppercase tracking-wider text-amber-500">Due Today</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['today']) }}</p>
        </button>
        <button type="button" wire:click="$set('statusFilter', 'week')" class="rounded-2xl bg-white p-5 text-left shadow ring-1 ring-black/5 transition hover:ring-teal-300 {{ $statusFilter === 'week' ? 'ring-2 ring-teal-500' : '' }}">
            <p class="text-xs font-bold uppercase tracking-wider text-teal-600">Due This Week</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['due_this_week']) }}</p>
        </button>
        <button type="button" wire:click="$set('statusFilter', 'none')" class="rounded-2xl bg-white p-5 text-left shadow ring-1 ring-black/5 transition hover:ring-slate-300 {{ $statusFilter === 'none' ? 'ring-2 ring-slate-500' : '' }}">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">No Follow-up Set</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['none']) }}</p>
        </button>
    </div>

    {{-- Filters --}}
    <div class="rounded-2xl bg-white p-4 shadow ring-1 ring-black/5">
        <div class="grid gap-3 lg:grid-cols-12">
            <div class="lg:col-span-3">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Search</label>
                <input type="text" wire:model.live.debounce.400ms="search" placeholder="Name, email, phone…" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
            </div>
            <div class="lg:col-span-3">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Owner</label>
                <select wire:model.live="ownerFilter" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    <option value="">All owners</option>
                    @foreach ($filterOptions['owners'] as $owner)
                        <option value="{{ $owner->id }}">{{ $owner->username }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-2">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Hotness</label>
                <select wire:model.live="hotnessFilter" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    <option value="">All</option>
                    @foreach ($hotnessOptions as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-3">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Status</label>
                <select wire:model.live="statusFilter" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    <option value="">All</option>
                    <option value="overdue">Overdue</option>
                    <option value="today">Due Today</option>
                    <option value="week">Due This Week</option>
                    <option value="upcoming">Upcoming (7d+)</option>
                    <option value="none">No Follow-up Set</option>
                </select>
            </div>
            <div class="flex items-end lg:col-span-1">
                <button type="button" wire:click="clearFilters" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                    Clear
                </button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow ring-1 ring-black/5" wire:loading.class="opacity-60" wire:target="search,ownerFilter,hotnessFilter,statusFilter,perPage,clearFollowUp">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Prospect</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Owner</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Hotness</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Next Follow-up</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Last Activity</th>
                        <th class="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider text-slate-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse ($rows as $prospect)
                        @php
                            $hotnessClass = match (strtolower((string) $prospect->hotness)) {
                                'hot' => 'bg-rose-100 text-rose-700',
                                'warm' => 'bg-amber-100 text-amber-700',
                                'cold' => 'bg-sky-100 text-sky-700',
                                default => 'bg-slate-100 text-slate-500',
                            };
                            $overdue = $prospect->next_follow_up && $prospect->next_follow_up->isPast();
                            $dueSoon = $prospect->next_follow_up && ! $overdue && $prospect->next_follow_up->lte(now()->addDays(7));
                        @endphp
                        <tr class="hover:bg-slate-50" wire:key="followup-row-{{ $prospect->id }}">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-teal-900">{{ trim($prospect->first_name.' '.$prospect->last_name) ?: 'Unnamed' }}</p>
                                <p class="text-xs text-slate-500">{{ $prospect->email ?: '—' }} @if($prospect->phone) &bull; {{ $prospect->phone }} @endif</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $prospect->user?->username ?? 'Unassigned' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($prospect->hotness)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase {{ $hotnessClass }}">{{ $prospect->hotness }}</span>
                                @else
                                    <span class="text-sm text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($prospect->next_follow_up)
                                    <span class="text-sm {{ $overdue ? 'font-bold text-rose-600' : ($dueSoon ? 'font-bold text-amber-600' : 'text-slate-600') }}">
                                        {{ $prospect->next_follow_up->format('M d, Y h:i A') }}
                                    </span>
                                    @if ($overdue)
                                        <span class="ml-1 inline-flex items-center rounded-full bg-rose-100 px-2 py-0.5 text-[10px] font-bold uppercase text-rose-700">Overdue</span>
                                    @endif
                                @else
                                    <span class="text-sm text-slate-400">Not set</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                @if ($prospect->latestActivity)
                                    {{ $prospect->latestActivity->action }}
                                    <span class="block text-xs text-slate-400">{{ $prospect->latestActivity->action_at?->diffForHumans() }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" wire:click="viewDetails({{ $prospect->id }})" class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-50 text-slate-700 font-semibold text-xs hover:bg-slate-100">View</button>
                                    <button type="button" wire:click="openLog({{ $prospect->id }})" class="inline-flex items-center px-3 py-1 rounded-lg bg-teal-50 text-teal-700 font-semibold text-xs hover:bg-teal-100">Log</button>
                                    <button type="button" wire:click="openReschedule({{ $prospect->id }})" class="inline-flex items-center px-3 py-1 rounded-lg bg-amber-50 text-amber-700 font-semibold text-xs hover:bg-amber-100">Reschedule</button>
                                    @if ($prospect->next_follow_up)
                                        <button type="button" wire:click="clearFollowUp({{ $prospect->id }})" wire:confirm="Clear this prospect's follow-up date?" class="inline-flex items-center px-3 py-1 rounded-lg bg-red-50 text-red-700 font-semibold text-xs hover:bg-red-100">Clear</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-500">No prospects match your filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-3 border-t border-slate-200 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <label>Rows per page</label>
                <select wire:model.live="perPage" class="rounded-lg border border-slate-300 px-2 py-1 text-xs text-slate-700 outline-none focus:border-teal-500">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                <span>&bull; {{ $rows->total() }} total</span>
            </div>
            <div>{{ $rows->links() }}</div>
        </div>
    </div>

    {{-- Log Activity Modal --}}
    @if ($loggingProspectId && $loggingProspect)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4" wire:click.self="cancelLog">
            <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl sm:p-8">
                <div class="flex items-start justify-between">
                    <h2 class="text-xl font-extrabold text-teal-800">Log Follow-up &mdash; {{ trim($loggingProspect->first_name.' '.$loggingProspect->last_name) ?: 'Unnamed' }}</h2>
                    <button type="button" wire:click="cancelLog" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveLog" class="mt-6 space-y-4">
                    <div>
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Activity Type</label>
                        <select wire:model="logAction" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                            @foreach ($activityOptions as $key => $option)
                                <option value="{{ $key }}">{{ $option['label'] }}</option>
                            @endforeach
                        </select>
                        @error('logAction') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Date &amp; Time</label>
                        <input type="datetime-local" wire:model="logDate" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                        @error('logDate') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Notes</label>
                        <textarea wire:model="logNotes" rows="2" placeholder="What happened?" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100"></textarea>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Next Follow-up (leave blank to clear)</label>
                        <input type="datetime-local" wire:model="logNextFollowUp" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-5">
                        <button type="button" wire:click="cancelLog" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                            Cancel
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="saveLog" class="rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-teal-700 disabled:opacity-60">
                            <span wire:loading.remove wire:target="saveLog">Save</span>
                            <span wire:loading wire:target="saveLog">Saving…</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Reschedule Modal --}}
    @if ($reschedulingProspectId && $reschedulingProspect)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4" wire:click.self="cancelReschedule">
            <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl">
                <h3 class="text-lg font-bold text-teal-800">Reschedule &mdash; {{ trim($reschedulingProspect->first_name.' '.$reschedulingProspect->last_name) ?: 'Unnamed' }}</h3>
                <form wire:submit.prevent="saveReschedule" class="mt-4 space-y-4">
                    <div>
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Next Follow-up</label>
                        <input type="datetime-local" wire:model="rescheduleDate" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                        @error('rescheduleDate') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex items-center justify-end gap-3">
                        <button type="button" wire:click="cancelReschedule" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                            Cancel
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="saveReschedule" class="rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-teal-700 disabled:opacity-60">
                            <span wire:loading.remove wire:target="saveReschedule">Save</span>
                            <span wire:loading wire:target="saveReschedule">Saving…</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Prospect Details / Activity History Modal --}}
    @if ($viewingProspectId && $viewingProspect)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4" wire:click.self="closeDetails">
            <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl sm:p-8">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-teal-600">Prospect Details</p>
                        <h2 class="mt-1 text-xl font-extrabold text-teal-800">{{ trim($viewingProspect->first_name.' '.$viewingProspect->last_name) ?: 'Unnamed' }}</h2>
                    </div>
                    <button type="button" wire:click="closeDetails" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="mt-5 grid gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm sm:grid-cols-2">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Owner</p>
                        <p class="text-slate-700">{{ $viewingProspect->user?->username ?? 'Unassigned' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Email</p>
                        <p class="text-slate-700">{{ $viewingProspect->email ?: '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Phone</p>
                        <p class="text-slate-700">{{ $viewingProspect->phone ?: '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Hotness</p>
                        <p class="text-slate-700">{{ $viewingProspect->hotness ?: '—' }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Next Follow-up</p>
                        <p class="text-slate-700">{{ $viewingProspect->next_follow_up ? $viewingProspect->next_follow_up->format('M d, Y g:ia') : 'Not set' }}</p>
                    </div>
                    @if ($viewingProspect->notes)
                        <div class="sm:col-span-2">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Notes</p>
                            <p class="text-slate-700">{{ $viewingProspect->notes }}</p>
                        </div>
                    @endif
                </div>

                <div class="mt-6">
                    <p class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-500">Activity History ({{ $viewingActivities->count() }})</p>

                    @if ($viewingActivities->isEmpty())
                        <div class="rounded-xl border border-dashed border-slate-200 p-8 text-center">
                            <p class="text-sm font-semibold text-slate-600">No activity logged yet</p>
                        </div>
                    @else
                        <ul class="space-y-3">
                            @foreach ($viewingActivities as $activity)
                                @php $option = $activityOptions[$activity->action] ?? null; @endphp
                                <li wire:key="view-activity-{{ $activity->id }}" class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white p-4">
                                    <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ \App\Models\Timeline::actionBadgeClass($activity->action) }}">
                                        <x-dashboard-icon :name="$option['icon'] ?? 'clock'" class="h-4.5 w-4.5" />
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold {{ \App\Models\Timeline::actionBadgeClass($activity->action) }}">{{ $option['label'] ?? $activity->action }}</span>
                                            <span class="text-xs text-slate-500">{{ $activity->action_at?->format('M d, Y g:ia') ?? '—' }}</span>
                                        </div>
                                        @if ($activity->notes)
                                            <p class="mt-2 text-sm text-slate-600">{{ $activity->notes }}</p>
                                        @endif
                                        @if ($activity->next_follow_up_dt)
                                            <p class="mt-2 inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-semibold text-amber-700">
                                                Next follow-up: {{ $activity->next_follow_up_dt->format('M d, Y g:ia') }}
                                            </p>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="mt-6 flex items-center justify-end gap-3 border-t border-slate-200 pt-5">
                    <button type="button" wire:click="closeDetails" class="rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-teal-700">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
