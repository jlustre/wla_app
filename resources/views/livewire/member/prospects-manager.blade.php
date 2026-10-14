<div class="space-y-6" wire:key="prospects-manager-root">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-600 dark:text-emerald-300">Workspace</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950 dark:text-white sm:text-3xl">My Prospects</h1>
            <p class="mt-2 max-w-2xl text-sm text-slate-500 dark:text-slate-400">Every lead you're personally working, in one place &mdash; add them by hand or import a batch from a spreadsheet.</p>
        </div>
        <div class="flex shrink-0 flex-wrap gap-2">
            <a href="{{ route('prospects.funnel') }}" wire:navigate class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                <x-dashboard-icon name="presentation-chart" class="h-4 w-4" />
                View Funnel
            </a>
            <button type="button" wire:click="openImport" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" /></svg>
                Import CSV
            </button>
            <button type="button" wire:click="openCreate" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Add Prospect
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-[28px] border border-slate-200/80 bg-white/90 p-5 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Total Prospects</p>
            <p class="mt-3 text-3xl font-extrabold text-slate-950 dark:text-white">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="rounded-[28px] border border-slate-200/80 bg-white/90 p-5 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-rose-500">Hot Leads</p>
            <p class="mt-3 text-3xl font-extrabold text-slate-950 dark:text-white">{{ number_format($stats['hot']) }}</p>
        </div>
        <div class="rounded-[28px] border border-slate-200/80 bg-white/90 p-5 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-500">Follow-ups Due (7d)</p>
            <p class="mt-3 text-3xl font-extrabold text-slate-950 dark:text-white">{{ number_format($stats['due_this_week']) }}</p>
        </div>
        <div class="rounded-[28px] border border-slate-200/80 bg-white/90 p-5 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-600 dark:text-emerald-300">Added This Week</p>
            <p class="mt-3 text-3xl font-extrabold text-slate-950 dark:text-white">{{ number_format($stats['added_this_week']) }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="rounded-[28px] border border-slate-200/80 bg-white/90 p-5 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
        <div class="grid gap-3 lg:grid-cols-12">
            <div class="lg:col-span-3">
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Search</label>
                <input type="text" wire:model.live.debounce.400ms="search" placeholder="Name, email, phone, source…" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:focus:ring-emerald-500/10">
            </div>
            <div class="lg:col-span-2">
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Source</label>
                <select wire:model.live="sourceFilter" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                    <option value="">All sources</option>
                    @foreach ($filterOptions['sources'] as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-2">
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Hotness</label>
                <select wire:model.live="hotnessFilter" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                    <option value="">All</option>
                    @foreach ($filterOptions['hotness'] as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-2">
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Follow-up from</label>
                <input type="date" wire:model.live="followUpFrom" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
            </div>
            <div class="lg:col-span-2">
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Follow-up to</label>
                <input type="date" wire:model.live="followUpTo" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
            </div>
            <div class="flex items-end lg:col-span-1">
                <button type="button" wire:click="clearFilters" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                    Clear
                </button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-[32px] border border-slate-200/80 bg-white/90 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85" wire:loading.class="opacity-60" wire:target="search,sourceFilter,hotnessFilter,followUpFrom,followUpTo,perPage,delete,import">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                <thead class="bg-slate-50/80 dark:bg-slate-800/70">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">
                            <button type="button" wire:click="toggleSort('first_name')" class="inline-flex items-center gap-1 hover:text-slate-900 dark:hover:text-white">
                                Prospect
                                @if ($sortBy === 'first_name') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Source</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">
                            <button type="button" wire:click="toggleSort('hotness')" class="inline-flex items-center gap-1 hover:text-slate-900 dark:hover:text-white">
                                Hotness
                                @if ($sortBy === 'hotness') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">
                            <button type="button" wire:click="toggleSort('next_follow_up')" class="inline-flex items-center gap-1 hover:text-slate-900 dark:hover:text-white">
                                Next Follow-up
                                @if ($sortBy === 'next_follow_up') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">
                            <button type="button" wire:click="toggleSort('created_at')" class="inline-flex items-center gap-1 hover:text-slate-900 dark:hover:text-white">
                                Added
                                @if ($sortBy === 'created_at') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/80 dark:divide-slate-800">
                    @forelse ($rows as $prospect)
                        @php
                            $hotnessClass = match (strtolower((string) $prospect->hotness)) {
                                'hot' => 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-300',
                                'warm' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-300',
                                'cold' => 'bg-sky-50 text-sky-600 dark:bg-sky-500/10 dark:text-sky-300',
                                default => 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400',
                            };
                            $dueSoon = $prospect->next_follow_up && $prospect->next_follow_up->isPast() === false && $prospect->next_follow_up->lte(now()->addDays(7));
                            $overdue = $prospect->next_follow_up && $prospect->next_follow_up->isPast();
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-white/[0.03]" wire:key="prospect-row-{{ $prospect->id }}">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-900 dark:text-white">{{ trim($prospect->first_name.' '.$prospect->last_name) ?: 'Unnamed' }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $prospect->email ?: '—' }} @if($prospect->phone) &bull; {{ $prospect->phone }} @endif</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">{{ $prospect->source ?: '—' }}</td>
                            <td class="px-6 py-4">
                                @if ($prospect->hotness)
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $hotnessClass }}">{{ $prospect->hotness }}</span>
                                @else
                                    <span class="text-sm text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm {{ $overdue ? 'font-semibold text-rose-600 dark:text-rose-400' : ($dueSoon ? 'font-semibold text-amber-600 dark:text-amber-400' : 'text-slate-600 dark:text-slate-300') }}">
                                {{ $prospect->next_follow_up ? $prospect->next_follow_up->format('M d, Y h:i A') : '—' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">{{ $prospect->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" wire:click="openActivityLog({{ $prospect->id }})" title="Activity Log" class="inline-flex h-8 w-8 items-center justify-center rounded-xl text-slate-500 transition hover:bg-emerald-50 hover:text-emerald-600 dark:text-slate-400 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">
                                        <x-dashboard-icon name="clipboard-document-list" class="h-4 w-4" />
                                    </button>
                                    <button type="button" wire:click="openEdit({{ $prospect->id }})" title="Edit" class="inline-flex h-8 w-8 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" /></svg>
                                    </button>
                                    <button type="button" wire:click="confirmDelete({{ $prospect->id }})" title="Delete" class="inline-flex h-8 w-8 items-center justify-center rounded-xl text-slate-500 transition hover:bg-rose-50 hover:text-rose-600 dark:text-slate-400 dark:hover:bg-rose-500/10 dark:hover:text-rose-300">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9M19.228 5.79c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397M4.772 5.79c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-14 text-center">
                                <svg class="mx-auto h-10 w-10 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" /></svg>
                                <p class="mt-3 text-sm font-semibold text-slate-700 dark:text-slate-200">No prospects match your filters</p>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Try clearing your filters, or add your first prospect to get started.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-3 border-t border-slate-200/80 px-6 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800">
            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                <label>Rows per page</label>
                <select wire:model.live="perPage" class="rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 outline-none focus:border-emerald-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                <span>&bull; {{ $rows->total() }} total</span>
            </div>
            <div>{{ $rows->links() }}</div>
        </div>
    </div>

    {{-- Create / Edit Modal --}}
    @if ($showFormModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm" wire:click.self="closeModal">
            <div class="max-h-[90vh] w-full max-w-xl overflow-y-auto rounded-[32px] border border-slate-200/80 bg-white p-6 shadow-2xl dark:border-slate-800 dark:bg-slate-900 sm:p-8">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-300">{{ $editingId ? 'Edit Prospect' : 'New Prospect' }}</p>
                        <h2 class="mt-1 text-xl font-bold text-slate-950 dark:text-white">{{ $editingId ? 'Update prospect details' : 'Add a prospect to your pipeline' }}</h2>
                    </div>
                    <button type="button" wire:click="closeModal" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="mt-6 space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">First Name</label>
                            <input type="text" wire:model="first_name" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                            @error('first_name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Last Name</label>
                            <input type="text" wire:model="last_name" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Email</label>
                            <input type="email" wire:model="email" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                            @error('email') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Phone</label>
                            <input type="text" wire:model="phone" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Source</label>
                            <select wire:model="source" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                <option value="">Select source…</option>
                                @foreach ($sourceOptions as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Hotness</label>
                            <select wire:model="hotness" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                <option value="">Not set</option>
                                @foreach ($hotnessOptions as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Next Follow-up</label>
                            <input type="datetime-local" wire:model="next_follow_up" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Notes</label>
                            <textarea wire:model="notes" rows="3" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100"></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-200/80 pt-5 dark:border-slate-800">
                        <button type="button" wire:click="closeModal" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                            Cancel
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="save" class="rounded-xl bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:opacity-60 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200">
                            <span wire:loading.remove wire:target="save">{{ $editingId ? 'Save Changes' : 'Add Prospect' }}</span>
                            <span wire:loading wire:target="save">Saving…</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete Confirm Modal --}}
    @if ($deletingId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm" wire:click.self="cancelDelete">
            <div class="w-full max-w-sm rounded-[28px] border border-slate-200/80 bg-white p-6 shadow-2xl dark:border-slate-800 dark:bg-slate-900">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 text-rose-500 dark:bg-rose-500/10">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
                </div>
                <h3 class="mt-4 text-lg font-bold text-slate-950 dark:text-white">Delete this prospect?</h3>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">This action can't be undone. Their record and notes will be permanently removed.</p>
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

    {{-- Import Modal --}}
    @if ($showImportModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm" wire:click.self="closeImport">
            <div class="w-full max-w-lg rounded-[32px] border border-slate-200/80 bg-white p-6 shadow-2xl dark:border-slate-800 dark:bg-slate-900 sm:p-8">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-300">Bulk Import</p>
                        <h2 class="mt-1 text-xl font-bold text-slate-950 dark:text-white">Import Prospects from CSV</h2>
                    </div>
                    <button type="button" wire:click="closeImport" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                @if ($importSummary)
                    <div class="mt-6 space-y-4">
                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-500/20 dark:bg-emerald-500/10">
                            <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-300">{{ $importSummary['imported'] }} prospect(s) imported successfully.</p>
                            @if ($importSummary['skipped'] > 0)
                                <p class="mt-1 text-sm text-emerald-700/80 dark:text-emerald-300/80">{{ $importSummary['skipped'] }} row(s) skipped.</p>
                            @endif
                        </div>

                        @if (! empty($importSummary['errors']))
                            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-500/20 dark:bg-amber-500/10">
                                <p class="text-xs font-semibold uppercase tracking-wide text-amber-700 dark:text-amber-300">Skipped rows</p>
                                <ul class="mt-2 space-y-1 text-sm text-amber-800 dark:text-amber-200">
                                    @foreach ($importSummary['errors'] as $error)
                                        <li>&bull; {{ $error }}</li>
                                    @endforeach
                                </ul>
                                @if ($importSummary['more_errors'] > 0)
                                    <p class="mt-2 text-xs text-amber-700 dark:text-amber-300">...and {{ $importSummary['more_errors'] }} more.</p>
                                @endif
                            </div>
                        @endif

                        <div class="flex justify-end gap-3">
                            <button type="button" wire:click="openImport" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                                Import Another File
                            </button>
                            <button type="button" wire:click="closeImport" class="rounded-xl bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200">
                                Done
                            </button>
                        </div>
                    </div>
                @else
                    <div class="mt-6 space-y-5">
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Upload a CSV with a header row. Supported columns:
                            <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs dark:bg-slate-800">first_name, last_name, email, phone, source, hotness, notes</code>.
                            Each row needs at least a name (first or last) and a way to reach them (email or phone).
                        </p>

                        <a href="{{ route('prospects.import-template') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 hover:text-emerald-700 dark:text-emerald-300 dark:hover:text-emerald-200">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" /></svg>
                            Download sample CSV
                        </a>

                        <form wire:submit.prevent="import">
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">CSV File</label>
                            <input type="file" wire:model="importFile" accept=".csv,text/csv" class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100 dark:text-slate-300 dark:file:bg-emerald-500/10 dark:file:text-emerald-300">
                            <div wire:loading wire:target="importFile" class="mt-2 text-xs text-slate-400">Uploading…</div>
                            @error('importFile') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror

                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" wire:click="closeImport" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                                    Cancel
                                </button>
                                <button type="submit" wire:loading.attr="disabled" wire:target="import" class="rounded-xl bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:opacity-60 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200">
                                    <span wire:loading.remove wire:target="import">Import</span>
                                    <span wire:loading wire:target="import">Importing…</span>
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Activity Log Modal --}}
    @if ($showActivityModal && $activityProspect)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm" wire:click.self="closeActivityLog">
            <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-[32px] border border-slate-200/80 bg-white p-6 shadow-2xl dark:border-slate-800 dark:bg-slate-900 sm:p-8">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-300">Activity Log</p>
                        <h2 class="mt-1 text-xl font-bold text-slate-950 dark:text-white">{{ trim($activityProspect->first_name.' '.$activityProspect->last_name) ?: 'Unnamed' }}</h2>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Every call, presentation, and touchpoint you've logged with this prospect.</p>
                    </div>
                    <button type="button" wire:click="closeActivityLog" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                {{-- Quick add / edit activity form --}}
                <form wire:submit.prevent="saveActivity" class="mt-6 space-y-4 rounded-2xl border border-slate-200/80 bg-slate-50/60 p-4 dark:border-slate-800 dark:bg-slate-800/40">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $editingActivityId ? 'Edit activity' : 'Log a new activity' }}</p>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Activity Type</label>
                            <select wire:model="activityAction" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                @foreach ($activityOptions as $key => $option)
                                    <option value="{{ $key }}">{{ $option['label'] }}</option>
                                @endforeach
                            </select>
                            @error('activityAction') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Date &amp; Time</label>
                            <input type="datetime-local" wire:model="activityDate" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                            @error('activityDate') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Notes</label>
                            <textarea wire:model="activityNotes" rows="2" placeholder="What happened? Any details worth remembering…" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"></textarea>
                            @error('activityNotes') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Next Follow-up (optional)</label>
                            <input type="datetime-local" wire:model="activityNextFollowUp" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                            @error('activityNextFollowUp') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        @if ($editingActivityId)
                            <button type="button" wire:click="cancelEditActivity" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                                Cancel
                            </button>
                        @endif
                        <button type="submit" wire:loading.attr="disabled" wire:target="saveActivity" class="rounded-xl bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:opacity-60 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200">
                            <span wire:loading.remove wire:target="saveActivity">{{ $editingActivityId ? 'Save Changes' : 'Add Activity' }}</span>
                            <span wire:loading wire:target="saveActivity">Saving…</span>
                        </button>
                    </div>
                </form>

                {{-- Activity timeline --}}
                <div class="mt-6">
                    <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">History ({{ $activities->count() }})</p>

                    @if ($activities->isEmpty())
                        <div class="rounded-2xl border border-dashed border-slate-200 p-8 text-center dark:border-slate-700">
                            <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">No activity logged yet</p>
                            <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">Log your first call, presentation, or meeting above.</p>
                        </div>
                    @else
                        <ul class="space-y-3">
                            @foreach ($activities as $activity)
                                @php $option = $activityOptions[$activity->action] ?? null; @endphp
                                <li wire:key="activity-{{ $activity->id }}" class="flex items-start gap-3 rounded-2xl border border-slate-200/80 bg-white p-4 dark:border-slate-800 dark:bg-slate-900/60">
                                    <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ \App\Models\Timeline::actionBadgeClass($activity->action) }}">
                                        <x-dashboard-icon :name="$option['icon'] ?? 'clock'" class="h-4.5 w-4.5" />
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center justify-between gap-2">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold {{ \App\Models\Timeline::actionBadgeClass($activity->action) }}">{{ $option['label'] ?? $activity->action }}</span>
                                                <span class="text-xs text-slate-500 dark:text-slate-400">{{ $activity->action_at?->format('M d, Y g:ia') ?? '—' }}</span>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <button type="button" wire:click="editActivity({{ $activity->id }})" title="Edit" class="inline-flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-800 dark:hover:bg-slate-800 dark:hover:text-slate-100">
                                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" /></svg>
                                                </button>
                                                <button type="button" wire:click="deleteActivity({{ $activity->id }})" wire:confirm="Remove this activity entry?" title="Delete" class="inline-flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 transition hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-500/10 dark:hover:text-rose-300">
                                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9M19.228 5.79c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397M4.772 5.79c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                                </button>
                                            </div>
                                        </div>
                                        @if ($activity->notes)
                                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $activity->notes }}</p>
                                        @endif
                                        @if ($activity->next_follow_up_dt)
                                            <p class="mt-2 inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-300">
                                                Next follow-up: {{ $activity->next_follow_up_dt->format('M d, Y g:ia') }}
                                            </p>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
