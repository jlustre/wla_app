<div class="w-full space-y-6" wire:key="admin-audit-logs-manager-root">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-600">Admin</p>
            <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-teal-800">Audit Logs</h1>
            <p class="mt-1 text-sm text-slate-500">A read-only trail of sensitive admin actions. Entries can't be edited or deleted here.</p>
        </div>
        <button type="button" wire:click="export" class="inline-flex items-center px-4 py-2 bg-white text-teal-700 ring-1 ring-black/5 rounded-xl font-semibold shadow hover:bg-slate-50 transition">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" /></svg>
            Export CSV
        </button>
    </div>

    {{-- Stats --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Events</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
            <p class="text-xs font-bold uppercase tracking-wider text-teal-600">Today</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['today']) }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
            <p class="text-xs font-bold uppercase tracking-wider text-amber-500">Last 7 Days</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['this_week']) }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Unique Actors</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['unique_actors']) }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="rounded-2xl bg-white p-4 shadow ring-1 ring-black/5">
        <div class="grid gap-3 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Search</label>
                <input type="text" wire:model.live.debounce.400ms="search" placeholder="Event, actor, IP, subject type…" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
            </div>
            <div class="lg:col-span-3">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Event</label>
                <select wire:model.live="eventFilter" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    <option value="">All events</option>
                    @foreach ($filterOptions['events'] as $event)
                        <option value="{{ $event }}">{{ \App\Livewire\Admin\AuditLogs\AuditLogsManager::eventLabel($event) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-2">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Actor</label>
                <select wire:model.live="actorFilter" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    <option value="">All actors</option>
                    @foreach ($filterOptions['actors'] as $actor)
                        <option value="{{ $actor->id }}">{{ $actor->username }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-3">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Subject Type</label>
                <select wire:model.live="typeFilter" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    <option value="">All types</option>
                    @foreach ($filterOptions['types'] as $type)
                        <option value="{{ $type }}">{{ \App\Livewire\Admin\AuditLogs\AuditLogsManager::auditableLabel($type) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-3">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">From</label>
                <input type="date" wire:model.live="dateFrom" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
            </div>
            <div class="lg:col-span-3">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">To</label>
                <input type="date" wire:model.live="dateTo" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
            </div>
            <div class="flex items-end lg:col-span-1">
                <button type="button" wire:click="clearFilters" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                    Clear
                </button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow ring-1 ring-black/5" wire:loading.class="opacity-60" wire:target="search,eventFilter,actorFilter,typeFilter,dateFrom,dateTo,perPage">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">
                            <button type="button" wire:click="toggleSort('created_at')" class="inline-flex items-center gap-1 hover:text-teal-700">
                                When
                                @if ($sortBy === 'created_at') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Actor</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">
                            <button type="button" wire:click="toggleSort('event')" class="inline-flex items-center gap-1 hover:text-teal-700">
                                Event
                                @if ($sortBy === 'event') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">IP</th>
                        <th class="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider text-slate-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse ($rows as $log)
                        <tr class="hover:bg-slate-50" wire:key="audit-row-{{ $log->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $log->created_at?->format('M d, Y g:i A') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="font-semibold text-teal-900">{{ $log->actor?->username ?? 'System' }}</p>
                                @if ($log->actor)
                                    <p class="text-xs text-slate-500">{{ $log->actor->email }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-full bg-teal-50 px-3 py-1 text-xs font-semibold text-teal-700">{{ \App\Livewire\Admin\AuditLogs\AuditLogsManager::eventLabel($log->event) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                {{ \App\Livewire\Admin\AuditLogs\AuditLogsManager::auditableLabel($log->auditable_type) }}
                                @if ($log->auditable_id)
                                    <span class="text-slate-400">#{{ $log->auditable_id }}</span>
                                @endif
                                @if ($log->auditable instanceof \App\Models\User)
                                    <span class="block text-xs text-slate-400">{{ $log->auditable->username }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $log->ip_address ?: '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <button type="button" wire:click="viewDetails({{ $log->id }})" class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-50 text-slate-700 font-semibold text-xs hover:bg-slate-100">View</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-500">No audit events match your filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-3 border-t border-slate-200 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <label>Rows per page</label>
                <select wire:model.live="perPage" class="rounded-lg border border-slate-300 px-2 py-1 text-xs text-slate-700 outline-none focus:border-teal-500">
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>&bull; {{ $rows->total() }} total</span>
            </div>
            <div>{{ $rows->links() }}</div>
        </div>
    </div>

    {{-- View Details Modal --}}
    @if ($viewingId && $viewingLog)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4" wire:click.self="closeDetails">
            <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl sm:p-8">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-teal-600">Audit Event</p>
                        <h2 class="mt-1 text-xl font-extrabold text-teal-800">{{ \App\Livewire\Admin\AuditLogs\AuditLogsManager::eventLabel($viewingLog->event) }}</h2>
                    </div>
                    <button type="button" wire:click="closeDetails" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="mt-4 grid gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm sm:grid-cols-2">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">When</p>
                        <p class="text-slate-700">{{ $viewingLog->created_at?->format('M d, Y g:i:s A') }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Actor</p>
                        <p class="text-slate-700">{{ $viewingLog->actor?->username ?? 'System' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Subject</p>
                        <p class="text-slate-700">
                            {{ \App\Livewire\Admin\AuditLogs\AuditLogsManager::auditableLabel($viewingLog->auditable_type) }}
                            @if ($viewingLog->auditable_id) #{{ $viewingLog->auditable_id }} @endif
                            @if ($viewingLog->auditable instanceof \App\Models\User)
                                ({{ $viewingLog->auditable->username }})
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">IP Address</p>
                        <p class="text-slate-700">{{ $viewingLog->ip_address ?: '—' }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">User Agent</p>
                        <p class="break-words text-slate-700">{{ $viewingLog->user_agent ?: '—' }}</p>
                    </div>
                </div>

                @if (! empty($viewingLog->old_values) || ! empty($viewingLog->new_values))
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div>
                            <p class="mb-2 text-xs font-bold uppercase tracking-wider text-slate-500">Before</p>
                            @if (empty($viewingLog->old_values))
                                <p class="text-sm text-slate-400">—</p>
                            @else
                                <dl class="space-y-1.5 rounded-xl border border-rose-100 bg-rose-50/50 p-3">
                                    @foreach ($viewingLog->old_values as $key => $value)
                                        <div class="text-xs">
                                            <dt class="font-semibold text-rose-700">{{ $key }}</dt>
                                            <dd class="text-slate-600">{{ is_scalar($value) ? $value : json_encode($value) }}</dd>
                                        </div>
                                    @endforeach
                                </dl>
                            @endif
                        </div>
                        <div>
                            <p class="mb-2 text-xs font-bold uppercase tracking-wider text-slate-500">After</p>
                            @if (empty($viewingLog->new_values))
                                <p class="text-sm text-slate-400">—</p>
                            @else
                                <dl class="space-y-1.5 rounded-xl border border-green-100 bg-green-50/50 p-3">
                                    @foreach ($viewingLog->new_values as $key => $value)
                                        <div class="text-xs">
                                            <dt class="font-semibold text-green-700">{{ $key }}</dt>
                                            <dd class="text-slate-600">{{ is_scalar($value) ? $value : json_encode($value) }}</dd>
                                        </div>
                                    @endforeach
                                </dl>
                            @endif
                        </div>
                    </div>
                @endif

                @if (! empty($viewingLog->metadata))
                    <div class="mt-6">
                        <p class="mb-2 text-xs font-bold uppercase tracking-wider text-slate-500">Metadata</p>
                        <dl class="space-y-1.5 rounded-xl border border-slate-200 bg-white p-3">
                            @foreach ($viewingLog->metadata as $key => $value)
                                <div class="text-xs">
                                    <dt class="font-semibold text-slate-600">{{ $key }}</dt>
                                    <dd class="text-slate-500">{{ is_scalar($value) ? $value : json_encode($value) }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>
                @endif

                <div class="mt-6 flex items-center justify-end gap-3 border-t border-slate-200 pt-5">
                    <button type="button" wire:click="closeDetails" class="rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-teal-700">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
