<div class="w-full space-y-6" wire:key="admin-activity-logs-manager-root">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-600">Admin</p>
            <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-teal-800">Activity Logs</h1>
            <p class="mt-1 text-sm text-slate-500">Member activity across the platform. Each entry is also shown to that member on their own dashboard, so entries can't be edited or deleted here.</p>
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
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Unique Members</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['unique_actors']) }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="rounded-2xl bg-white p-4 shadow ring-1 ring-black/5">
        <div class="grid gap-3 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Search</label>
                <input type="text" wire:model.live.debounce.400ms="search" placeholder="Title, description, type, member…" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
            </div>
            <div class="lg:col-span-3">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Type</label>
                <select wire:model.live="typeFilter" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    <option value="">All types</option>
                    @foreach ($filterOptions['types'] as $type)
                        <option value="{{ $type }}">{{ \App\Livewire\Admin\ActivityLogs\ActivityLogsManager::typeLabel($type) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-2">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Member</label>
                <select wire:model.live="actorFilter" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    <option value="">All members</option>
                    @foreach ($filterOptions['actors'] as $actor)
                        <option value="{{ $actor->id }}">{{ $actor->username }}</option>
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
    <div class="overflow-hidden rounded-2xl bg-white shadow ring-1 ring-black/5" wire:loading.class="opacity-60" wire:target="search,typeFilter,actorFilter,dateFrom,dateTo,perPage">
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
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">
                            <button type="button" wire:click="toggleSort('type')" class="inline-flex items-center gap-1 hover:text-teal-700">
                                Type
                                @if ($sortBy === 'type') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">
                            <button type="button" wire:click="toggleSort('title')" class="inline-flex items-center gap-1 hover:text-teal-700">
                                Title
                                @if ($sortBy === 'title') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider text-slate-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse ($rows as $activity)
                        <tr class="hover:bg-slate-50" wire:key="activity-row-{{ $activity->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $activity->created_at?->format('M d, Y g:i A') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="font-semibold text-teal-900">{{ $activity->user?->username ?? 'System' }}</p>
                                @if ($activity->user)
                                    <p class="text-xs text-slate-500">{{ $activity->user->email }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ \App\Livewire\Admin\ActivityLogs\ActivityLogsManager::typeBadgeClass($activity->type) }}">{{ \App\Livewire\Admin\ActivityLogs\ActivityLogsManager::typeLabel($activity->type) }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $activity->title }}
                                @if ($activity->subject_type)
                                    <span class="block text-xs text-slate-400">{{ \App\Livewire\Admin\ActivityLogs\ActivityLogsManager::subjectLabel($activity->subject_type) }} #{{ $activity->subject_id }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <button type="button" wire:click="viewDetails({{ $activity->id }})" class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-50 text-slate-700 font-semibold text-xs hover:bg-slate-100">View</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500">No activity matches your filters.</td>
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
    @if ($viewingId && $viewingActivity)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4" wire:click.self="closeDetails">
            <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl sm:p-8">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-teal-600">Activity</p>
                        <h2 class="mt-1 text-xl font-extrabold text-teal-800">{{ $viewingActivity->title }}</h2>
                    </div>
                    <button type="button" wire:click="closeDetails" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="mt-4 grid gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm sm:grid-cols-2">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">When</p>
                        <p class="text-slate-700">{{ $viewingActivity->created_at?->format('M d, Y g:i:s A') }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Member</p>
                        <p class="text-slate-700">{{ $viewingActivity->user?->username ?? 'System' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Type</p>
                        <p class="text-slate-700">{{ \App\Livewire\Admin\ActivityLogs\ActivityLogsManager::typeLabel($viewingActivity->type) }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Subject</p>
                        <p class="text-slate-700">
                            @if ($viewingActivity->subject_type)
                                {{ \App\Livewire\Admin\ActivityLogs\ActivityLogsManager::subjectLabel($viewingActivity->subject_type) }} #{{ $viewingActivity->subject_id }}
                            @else
                                —
                            @endif
                        </p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">IP Address</p>
                        <p class="text-slate-700">{{ $viewingActivity->ip_address ?: '—' }}</p>
                    </div>
                </div>

                @if ($viewingActivity->description)
                    <div class="mt-4">
                        <p class="mb-1 text-xs font-bold uppercase tracking-wider text-slate-500">Description</p>
                        <p class="text-sm text-slate-700">{{ $viewingActivity->description }}</p>
                    </div>
                @endif

                @if (! empty($viewingActivity->metadata))
                    <div class="mt-4">
                        <p class="mb-2 text-xs font-bold uppercase tracking-wider text-slate-500">Metadata</p>
                        <dl class="space-y-1.5 rounded-xl border border-slate-200 bg-white p-3">
                            @foreach ($viewingActivity->metadata as $key => $value)
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
