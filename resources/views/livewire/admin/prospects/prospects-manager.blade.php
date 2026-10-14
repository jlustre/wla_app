<div class="w-full space-y-6" wire:key="admin-prospects-manager-root">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-extrabold text-teal-800">Prospects</h1>
        <button type="button" wire:click="openCreate" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-xl font-semibold shadow hover:bg-teal-700 transition">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Prospect
        </button>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-xl bg-green-100 text-green-800 font-semibold">{{ session('success') }}</div>
    @endif

    {{-- Stats --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Prospects</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
            <p class="text-xs font-bold uppercase tracking-wider text-rose-500">Hot Leads</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['hot']) }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
            <p class="text-xs font-bold uppercase tracking-wider text-amber-500">Follow-ups Due (7d)</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['due_this_week']) }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
            <p class="text-xs font-bold uppercase tracking-wider text-teal-600">Added This Week</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['added_this_week']) }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="rounded-2xl bg-white p-4 shadow ring-1 ring-black/5">
        <div class="grid gap-3 lg:grid-cols-12">
            <div class="lg:col-span-3">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Search</label>
                <input type="text" wire:model.live.debounce.400ms="search" placeholder="Name, email, phone, source…" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
            </div>
            <div class="lg:col-span-2">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Owner</label>
                <select wire:model.live="ownerFilter" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    <option value="">All owners</option>
                    @foreach ($filterOptions['owners'] as $owner)
                        <option value="{{ $owner->id }}">{{ $owner->username }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-2">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Source</label>
                <select wire:model.live="sourceFilter" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    <option value="">All sources</option>
                    @foreach ($filterOptions['sources'] as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-2">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Hotness</label>
                <select wire:model.live="hotnessFilter" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    <option value="">All</option>
                    @foreach ($filterOptions['hotness'] as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-2">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Follow-up from</label>
                <input type="date" wire:model.live="followUpFrom" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
            </div>
            <div class="flex items-end lg:col-span-1">
                <button type="button" wire:click="clearFilters" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                    Clear
                </button>
            </div>
        </div>
        <div class="mt-3 grid gap-3 lg:grid-cols-12">
            <div class="lg:col-span-2">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Follow-up to</label>
                <input type="date" wire:model.live="followUpTo" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow ring-1 ring-black/5" wire:loading.class="opacity-60" wire:target="search,sourceFilter,hotnessFilter,ownerFilter,followUpFrom,followUpTo,perPage,delete">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">
                            <button type="button" wire:click="toggleSort('first_name')" class="inline-flex items-center gap-1 hover:text-teal-700">
                                Prospect
                                @if ($sortBy === 'first_name') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Owner</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Source</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">
                            <button type="button" wire:click="toggleSort('hotness')" class="inline-flex items-center gap-1 hover:text-teal-700">
                                Hotness
                                @if ($sortBy === 'hotness') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">
                            <button type="button" wire:click="toggleSort('next_follow_up')" class="inline-flex items-center gap-1 hover:text-teal-700">
                                Next Follow-up
                                @if ($sortBy === 'next_follow_up') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">
                            <button type="button" wire:click="toggleSort('created_at')" class="inline-flex items-center gap-1 hover:text-teal-700">
                                Added
                                @if ($sortBy === 'created_at') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
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
                            $dueSoon = $prospect->next_follow_up && $prospect->next_follow_up->isPast() === false && $prospect->next_follow_up->lte(now()->addDays(7));
                            $overdue = $prospect->next_follow_up && $prospect->next_follow_up->isPast();
                        @endphp
                        <tr class="hover:bg-slate-50" wire:key="prospect-row-{{ $prospect->id }}">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-teal-900">{{ trim($prospect->first_name.' '.$prospect->last_name) ?: 'Unnamed' }}</p>
                                <p class="text-xs text-slate-500">{{ $prospect->email ?: '—' }} @if($prospect->phone) &bull; {{ $prospect->phone }} @endif</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $prospect->user?->username ?? 'Unassigned' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $prospect->source ?: '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($prospect->hotness)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase {{ $hotnessClass }}">{{ $prospect->hotness }}</span>
                                @else
                                    <span class="text-sm text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $overdue ? 'font-semibold text-rose-600' : ($dueSoon ? 'font-semibold text-amber-600' : 'text-slate-600') }}">
                                {{ $prospect->next_follow_up ? $prospect->next_follow_up->format('M d, Y h:i A') : '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $prospect->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" wire:click="openEdit({{ $prospect->id }})" class="inline-flex items-center px-3 py-1 rounded-lg bg-emerald-50 text-emerald-700 font-semibold text-xs hover:bg-emerald-100">Edit</button>
                                    <button type="button" wire:click="confirmDelete({{ $prospect->id }})" class="inline-flex items-center px-3 py-1 rounded-lg bg-red-50 text-red-700 font-semibold text-xs hover:bg-red-100">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-sm text-slate-500">No prospects match your filters.</td>
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

    {{-- Create / Edit Modal --}}
    @if ($showFormModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4" wire:click.self="closeModal">
            <div class="max-h-[90vh] w-full max-w-xl overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl sm:p-8">
                <div class="flex items-start justify-between">
                    <h2 class="text-xl font-extrabold text-teal-800">{{ $editingId ? 'Edit Prospect' : 'Add Prospect' }}</h2>
                    <button type="button" wire:click="closeModal" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="mt-6 space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Owner</label>
                            <select wire:model="user_id" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                                <option value="">Select owner…</option>
                                @foreach ($allOwners as $owner)
                                    <option value="{{ $owner->id }}">{{ $owner->username }}</option>
                                @endforeach
                            </select>
                            @error('user_id') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">First Name</label>
                            <input type="text" wire:model="first_name" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                            @error('first_name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Last Name</label>
                            <input type="text" wire:model="last_name" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Email</label>
                            <input type="email" wire:model="email" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                            @error('email') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Phone</label>
                            <input type="text" wire:model="phone" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Source</label>
                            <select wire:model="source" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                                <option value="">Select source…</option>
                                @foreach ($sourceOptions as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Hotness</label>
                            <select wire:model="hotness" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                                <option value="">Not set</option>
                                @foreach ($hotnessOptions as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Next Follow-up</label>
                            <input type="datetime-local" wire:model="next_follow_up" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Notes</label>
                            <textarea wire:model="notes" rows="3" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100"></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-5">
                        <button type="button" wire:click="closeModal" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                            Cancel
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="save" class="rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-teal-700 disabled:opacity-60">
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
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4" wire:click.self="cancelDelete">
            <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl">
                <h3 class="text-lg font-bold text-teal-800">Delete this prospect?</h3>
                <p class="mt-2 text-sm text-slate-500">This action can't be undone. Their record and notes will be permanently removed.</p>
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" wire:click="cancelDelete" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="button" wire:click="delete" class="rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-rose-500">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
