<div class="w-full space-y-6" wire:key="admin-sponsor-relationships-manager-root">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-600">Admin</p>
            <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-teal-800">Sponsor Relationships</h1>
            <p class="mt-1 text-sm text-slate-500">The historical log of sponsor links. To change who actually sponsors a member, use <a href="{{ route('admin.downline') }}" wire:navigate class="font-semibold text-teal-700 hover:underline">Downline Tree</a> — editing this log doesn't change a member's live sponsor.</p>
        </div>
        <div class="flex shrink-0 gap-2">
            <button type="button" wire:click="openBackfillConfirm" class="inline-flex items-center px-4 py-2 bg-white text-teal-700 ring-1 ring-black/5 rounded-xl font-semibold shadow hover:bg-slate-50 transition">
                Backfill Missing
            </button>
            <button type="button" wire:click="openCreate" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-xl font-semibold shadow hover:bg-teal-700 transition">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Log Entry
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-xl bg-green-100 text-green-800 font-semibold">{{ session('success') }}</div>
    @endif

    {{-- Stats --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <button type="button" wire:click="$set('statusFilter', '')" class="rounded-2xl bg-white p-5 text-left shadow ring-1 ring-black/5 transition hover:ring-teal-300 {{ $statusFilter === '' ? 'ring-2 ring-teal-500' : '' }}">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Log Rows</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['total']) }}</p>
        </button>
        <button type="button" wire:click="$set('statusFilter', 'current')" class="rounded-2xl bg-white p-5 text-left shadow ring-1 ring-black/5 transition hover:ring-green-300 {{ $statusFilter === 'current' ? 'ring-2 ring-green-500' : '' }}">
            <p class="text-xs font-bold uppercase tracking-wider text-green-600">Marked Current</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['current']) }}</p>
        </button>
        <button type="button" wire:click="$set('statusFilter', 'ended')" class="rounded-2xl bg-white p-5 text-left shadow ring-1 ring-black/5 transition hover:ring-slate-300 {{ $statusFilter === 'ended' ? 'ring-2 ring-slate-500' : '' }}">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Ended</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['ended']) }}</p>
        </button>
        <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
            <p class="text-xs font-bold uppercase tracking-wider text-amber-500">Live Links Missing From Log</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['missing']) }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="rounded-2xl bg-white p-4 shadow ring-1 ring-black/5">
        <div class="grid gap-3 lg:grid-cols-12">
            <div class="lg:col-span-6">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Search</label>
                <input type="text" wire:model.live.debounce.400ms="search" placeholder="Member, sponsor, or sponsor code…" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
            </div>
            <div class="lg:col-span-4">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Status</label>
                <select wire:model.live="statusFilter" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    <option value="">All</option>
                    <option value="current">Marked Current</option>
                    <option value="ended">Ended</option>
                    <option value="trashed">Deleted</option>
                </select>
            </div>
            <div class="flex items-end lg:col-span-2">
                <button type="button" wire:click="clearFilters" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                    Clear
                </button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow ring-1 ring-black/5" wire:loading.class="opacity-60" wire:target="search,statusFilter,perPage,delete,restore,runBackfill">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Sponsor</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">
                            <button type="button" wire:click="toggleSort('is_current')" class="inline-flex items-center gap-1 hover:text-teal-700">
                                Status
                                @if ($sortBy === 'is_current') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">
                            <button type="button" wire:click="toggleSort('linked_at')" class="inline-flex items-center gap-1 hover:text-teal-700">
                                Linked
                                @if ($sortBy === 'linked_at') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">
                            <button type="button" wire:click="toggleSort('ended_at')" class="inline-flex items-center gap-1 hover:text-teal-700">
                                Ended
                                @if ($sortBy === 'ended_at') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider text-slate-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse ($rows as $row)
                        <tr class="hover:bg-slate-50 {{ $row->trashed() ? 'opacity-60' : '' }}" wire:key="sr-row-{{ $row->id }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="font-semibold text-teal-900">{{ $row->member?->username ?? 'Unknown' }}</p>
                                <p class="text-xs text-slate-500">{{ $row->member?->email }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $row->sponsor?->username ?? 'Unassigned' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $row->sponsor_code ?: '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($row->trashed())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase bg-slate-200 text-slate-600">Deleted</span>
                                @elseif ($row->is_current)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase bg-green-100 text-green-700">Current</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase bg-slate-100 text-slate-500">Ended</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $row->linked_at?->format('M d, Y') ?? 'Not recorded' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $row->ended_at?->format('M d, Y') ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" wire:click="viewDetails({{ $row->id }})" class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-50 text-slate-700 font-semibold text-xs hover:bg-slate-100">View</button>
                                    @if ($row->trashed())
                                        <button type="button" wire:click="restore({{ $row->id }})" class="inline-flex items-center px-3 py-1 rounded-lg bg-green-50 text-green-700 font-semibold text-xs hover:bg-green-100">Restore</button>
                                    @else
                                        <button type="button" wire:click="openEdit({{ $row->id }})" class="inline-flex items-center px-3 py-1 rounded-lg bg-emerald-50 text-emerald-700 font-semibold text-xs hover:bg-emerald-100">Edit</button>
                                        <button type="button" wire:click="confirmDelete({{ $row->id }})" class="inline-flex items-center px-3 py-1 rounded-lg bg-red-50 text-red-700 font-semibold text-xs hover:bg-red-100">Delete</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-sm text-slate-500">No sponsor relationship records match your filters.</td>
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
                    <h2 class="text-xl font-extrabold text-teal-800">{{ $editingId ? 'Edit Log Entry' : 'Log a Sponsor Relationship' }}</h2>
                    <button type="button" wire:click="closeModal" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs font-semibold text-amber-700">
                    This only edits the history log — it does not change the member's actual current sponsor. Use Downline Tree for real reassignments.
                </div>

                <form wire:submit.prevent="save" class="mt-6 space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Member</label>
                            <select wire:model="member_id" @disabled($editingId) class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 disabled:bg-slate-100 disabled:text-slate-400">
                                <option value="">Select member…</option>
                                @foreach ($userOptions as $option)
                                    <option value="{{ $option->id }}">{{ $option->username }}</option>
                                @endforeach
                            </select>
                            @error('member_id') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Sponsor</label>
                            <select wire:model="sponsor_id" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                                <option value="">No sponsor</option>
                                @foreach ($userOptions as $option)
                                    <option value="{{ $option->id }}">{{ $option->username }}</option>
                                @endforeach
                            </select>
                            @error('sponsor_id') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Sponsor Code</label>
                            <input type="text" wire:model="sponsor_code" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                        </div>
                        <div class="flex items-end pb-2.5">
                            <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700">
                                <input type="checkbox" wire:model="is_current" class="h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                                Mark as current
                            </label>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Linked At</label>
                            <input type="datetime-local" wire:model="linked_at" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                            @error('linked_at') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Ended At</label>
                            <input type="datetime-local" wire:model="ended_at" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                            @error('ended_at') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Reason / Note</label>
                            <textarea wire:model="reason" rows="2" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100"></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-5">
                        <button type="button" wire:click="closeModal" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                            Cancel
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="save" class="rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-teal-700 disabled:opacity-60">
                            <span wire:loading.remove wire:target="save">{{ $editingId ? 'Save Changes' : 'Add Entry' }}</span>
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
                <h3 class="text-lg font-bold text-teal-800">Delete this log entry?</h3>
                <p class="mt-2 text-sm text-slate-500">It will be soft-deleted and can be restored later from the Deleted filter. This never affects the member's live sponsor.</p>
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

    {{-- Backfill Confirm Modal --}}
    @if ($showBackfillModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4" wire:click.self="cancelBackfill">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                <h3 class="text-lg font-bold text-teal-800">Backfill missing log entries?</h3>
                <p class="mt-2 text-sm text-slate-500">
                    {{ number_format($stats['missing']) }} member(s) have a live sponsor (<code class="rounded bg-slate-100 px-1 py-0.5 text-xs">users.sponsor_id</code>) with no matching "current" row in this log.
                    This creates one for each, dated from their last update — it does not change anyone's actual sponsor.
                </p>
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" wire:click="cancelBackfill" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="button" wire:click="runBackfill" wire:loading.attr="disabled" wire:target="runBackfill" class="rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-teal-700 disabled:opacity-60">
                        <span wire:loading.remove wire:target="runBackfill">Backfill {{ number_format($stats['missing']) }}</span>
                        <span wire:loading wire:target="runBackfill">Backfilling…</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- View Details Modal --}}
    @if ($viewingId && $viewingRow)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4" wire:click.self="closeDetails">
            <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl sm:p-8">
                <div class="flex items-start justify-between">
                    <p class="text-xs font-bold uppercase tracking-wider text-teal-600">Sponsor Relationship Log</p>
                    <button type="button" wire:click="closeDetails" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="mt-4 grid gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm sm:grid-cols-2">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Member</p>
                        <p class="text-slate-700">{{ $viewingRow->member?->username ?? 'Unknown' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sponsor</p>
                        <p class="text-slate-700">{{ $viewingRow->sponsor?->username ?? 'Unassigned' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sponsor Code</p>
                        <p class="text-slate-700">{{ $viewingRow->sponsor_code ?: '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Status</p>
                        <p class="text-slate-700">{{ $viewingRow->trashed() ? 'Deleted' : ($viewingRow->is_current ? 'Current' : 'Ended') }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Linked At</p>
                        <p class="text-slate-700">{{ $viewingRow->linked_at?->format('M d, Y g:ia') ?? 'Not recorded' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Ended At</p>
                        <p class="text-slate-700">{{ $viewingRow->ended_at?->format('M d, Y g:ia') ?? '—' }}</p>
                    </div>
                    @if (! empty($viewingRow->metadata['reason']))
                        <div class="sm:col-span-2">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Reason / Note</p>
                            <p class="text-slate-700">{{ $viewingRow->metadata['reason'] }}</p>
                        </div>
                    @endif
                    @if (! empty($viewingRow->metadata['source']))
                        <div class="sm:col-span-2">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Source</p>
                            <p class="text-slate-700">{{ $viewingRow->metadata['source'] }}</p>
                        </div>
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
