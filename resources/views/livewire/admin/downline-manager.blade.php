<div class="space-y-6" wire:key="downline-manager-root">

    {{-- Stats --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <div class="rounded-3xl border border-sky-400/15 bg-gradient-to-br from-slate-900 to-slate-950 p-5 shadow-xl">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-300">Total Members</p>
            <p class="mt-3 text-3xl font-extrabold text-white">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="rounded-3xl border border-emerald-400/15 bg-gradient-to-br from-slate-900 to-slate-950 p-5 shadow-xl">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-300">Active Members</p>
            <p class="mt-3 text-3xl font-extrabold text-white">{{ number_format($stats['active']) }}</p>
        </div>
        <div class="rounded-3xl border border-amber-400/15 bg-gradient-to-br from-slate-900 to-slate-950 p-5 shadow-xl">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-300">Root / Unsponsored</p>
            <p class="mt-3 text-3xl font-extrabold text-white">{{ number_format($stats['roots']) }}</p>
            <p class="mt-2 text-sm text-slate-400">Members with no sponsor on file</p>
        </div>
        <div class="rounded-3xl border border-sky-400/15 bg-gradient-to-br from-slate-900 to-slate-950 p-5 shadow-xl">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-300">Avg. Direct Referrals</p>
            <p class="mt-3 text-3xl font-extrabold text-white">{{ $stats['avgDirect'] }}</p>
            <p class="mt-2 text-sm text-slate-400">Per sponsor with at least one</p>
        </div>
        <div class="rounded-3xl border border-sky-400/15 bg-gradient-to-br from-slate-900 to-slate-950 p-5 shadow-xl">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-300">Deepest Branch</p>
            <p class="mt-3 text-3xl font-extrabold text-white">{{ $stats['maxDepth'] }}</p>
            <p class="mt-2 text-sm text-slate-400">Levels from any root</p>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-400/30 bg-emerald-500/10 p-4 text-sm text-emerald-200">{{ session('success') }}</div>
    @endif

    {{-- Tabs --}}
    <div class="rounded-3xl border border-white/10 bg-white/5 p-2 shadow-xl">
        <div class="flex flex-wrap gap-2">
            <button type="button" wire:click="$set('tab', 'directory')" class="rounded-2xl px-4 py-2 text-sm font-semibold transition {{ $tab === 'directory' ? 'bg-sky-500 text-white' : 'text-slate-300 hover:bg-white/5' }}">
                Member Directory
            </button>
            <button type="button" wire:click="$set('tab', 'tree')" class="rounded-2xl px-4 py-2 text-sm font-semibold transition {{ $tab === 'tree' ? 'bg-sky-500 text-white' : 'text-slate-300 hover:bg-white/5' }}">
                Tree Explorer
            </button>
        </div>
    </div>

    {{-- Member Directory --}}
    @if ($tab === 'directory')
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-300">Member Directory</p>
                    <h2 class="mt-2 text-2xl font-extrabold text-white">Search &amp; Manage Any Member</h2>
                </div>
                <div class="flex flex-wrap gap-2">
                    <input type="text" wire:model.live.debounce.400ms="search" placeholder="Search name or email…" class="rounded-xl border border-white/10 bg-slate-900/70 px-4 py-2.5 text-sm text-slate-100 placeholder:text-slate-500 outline-none focus:border-sky-400">
                    <select wire:model.live="statusFilter" class="rounded-xl border border-white/10 bg-slate-900/70 px-4 py-2.5 text-sm text-slate-100 outline-none focus:border-sky-400">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="pending">Pending</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="text-slate-400">
                        <tr class="border-b border-white/10">
                            <th class="px-4 py-3 font-semibold">
                                <button type="button" wire:click="toggleSort('username')" class="inline-flex items-center gap-1 hover:text-white">
                                    Name
                                    @if ($sortBy === 'username') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                                </button>
                            </th>
                            <th class="px-4 py-3 font-semibold">Sponsor</th>
                            <th class="px-4 py-3 font-semibold">
                                <button type="button" wire:click="toggleSort('status')" class="inline-flex items-center gap-1 hover:text-white">
                                    Status
                                    @if ($sortBy === 'status') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                                </button>
                            </th>
                            <th class="px-4 py-3 font-semibold">
                                <button type="button" wire:click="toggleSort('direct_members_count')" class="inline-flex items-center gap-1 hover:text-white">
                                    Direct
                                    @if ($sortBy === 'direct_members_count') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                                </button>
                            </th>
                            <th class="px-4 py-3 font-semibold">Downline</th>
                            <th class="px-4 py-3 font-semibold">Companies</th>
                            <th class="px-4 py-3 font-semibold">
                                <button type="button" wire:click="toggleSort('created_at')" class="inline-flex items-center gap-1 hover:text-white">
                                    Joined
                                    @if ($sortBy === 'created_at') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                                </button>
                            </th>
                            <th class="px-4 py-3 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 text-slate-300">
                        @forelse ($rows as $member)
                            @php
                                $statusValue = $member->status?->value ?? $member->status ?? 'active';
                                $statusClass = match ($statusValue) {
                                    'active' => 'bg-emerald-500/10 text-emerald-300',
                                    'suspended' => 'bg-red-500/10 text-red-300',
                                    'pending' => 'bg-amber-500/10 text-amber-300',
                                    default => 'bg-slate-500/10 text-slate-300',
                                };
                            @endphp
                            <tr class="hover:bg-white/[0.03]">
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-white">{{ $member->username }}</p>
                                    <p class="text-xs text-slate-500">{{ $member->email }}</p>
                                </td>
                                <td class="px-4 py-4">{{ $member->sponsor?->username ?? '—' }}</td>
                                <td class="px-4 py-4"><span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">{{ \Illuminate\Support\Str::headline($statusValue) }}</span></td>
                                <td class="px-4 py-4">{{ $member->direct_members_count }}</td>
                                <td class="px-4 py-4">{{ $member->downlineCount() }}</td>
                                <td class="px-4 py-4">{{ $member->active_companies_count }}</td>
                                <td class="px-4 py-4">{{ optional($member->created_at)->format('M d, Y') }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex gap-2">
                                        <button type="button" wire:click="viewInTree({{ $member->id }})" class="rounded-lg border border-white/10 px-3 py-1.5 text-xs font-semibold text-white hover:bg-white/10">View Tree</button>
                                        <button type="button" wire:click="startReassign({{ $member->id }})" class="rounded-lg border border-sky-400/30 px-3 py-1.5 text-xs font-semibold text-sky-300 hover:bg-sky-500/10">Reassign</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-slate-400">No members match your search.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $rows->links() }}
            </div>
        </div>
    @endif

    {{-- Tree Explorer --}}
    @if ($tab === 'tree')
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-300">Tree Explorer</p>
                    <h2 class="mt-2 text-2xl font-extrabold text-white">
                        {{ $viewingTreeRootId ? 'Branch View' : 'Pick a member from the directory to explore their branch' }}
                    </h2>
                </div>
                @if ($viewingTreeRootId)
                    <button type="button" wire:click="$set('tab', 'directory')" class="rounded-xl border border-white/10 px-4 py-2 text-sm font-semibold text-slate-200 hover:bg-white/10">Back to Directory</button>
                @endif
            </div>

            <div class="mt-6 min-h-[500px] overflow-hidden rounded-2xl bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 p-4">
                @if ($viewingTreeRootId)
                    <livewire:dashboard.sponsorship-tree :root-id="$viewingTreeRootId" :key="'admin-tree-'.$viewingTreeRootId" />
                @else
                    <livewire:dashboard.sponsorship-tree :root-id="auth()->id()" :key="'admin-tree-self'" />
                @endif
            </div>
        </div>
    @endif

    {{-- Reassign Sponsor Modal --}}
    @if ($reassigningUserId)
        @php $reassigningUser = \App\Models\User::find($reassigningUserId); @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/70 p-4" wire:click.self="cancelReassign">
            <div class="w-full max-w-lg rounded-3xl border border-white/10 bg-slate-900 p-6 shadow-2xl">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-300">Admin Override</p>
                <h3 class="mt-2 text-xl font-extrabold text-white">Reassign Sponsor for {{ $reassigningUser?->username }}</h3>
                <p class="mt-2 text-sm text-slate-400">Current sponsor: <span class="font-semibold text-slate-200">{{ $reassigningUser?->sponsor?->username ?? 'None' }}</span></p>

                <div class="mt-5">
                    <label class="mb-2 block text-sm font-semibold text-slate-200">New Sponsor</label>
                    @if ($newSponsorLabel)
                        <div class="flex items-center justify-between rounded-xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                            <span>{{ $newSponsorLabel }}</span>
                            <button type="button" wire:click="$set('newSponsorId', null)" class="text-xs font-semibold text-emerald-300 hover:text-emerald-100">Change</button>
                        </div>
                    @else
                        <input type="text" wire:model.live.debounce.300ms="newSponsorSearch" placeholder="Search by username or email…" class="w-full rounded-xl border border-white/10 bg-slate-800/70 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-500 outline-none focus:border-sky-400">
                        @if ($newSponsorSearch !== '')
                            <div class="mt-2 max-h-48 overflow-y-auto rounded-xl border border-white/10 bg-slate-800/70">
                                @forelse ($sponsorOptions as $option)
                                    <button type="button" wire:click="pickSponsor({{ $option->id }}, {{ \Illuminate\Support\Js::from($option->username.' ('.$option->email.')') }})" class="flex w-full items-center justify-between px-4 py-2.5 text-left text-sm text-slate-200 hover:bg-white/5">
                                        <span>{{ $option->username }}</span>
                                        <span class="text-xs text-slate-500">{{ $option->email }}</span>
                                    </button>
                                @empty
                                    <p class="px-4 py-3 text-sm text-slate-500">No matching members.</p>
                                @endforelse
                            </div>
                        @endif
                    @endif
                    @error('newSponsorId') <p class="mt-2 text-xs text-rose-400">{{ $message }}</p> @enderror
                </div>

                <div class="mt-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-200">Reason (optional)</label>
                    <textarea wire:model="reassignReason" rows="2" placeholder="Why is this sponsor being changed?" class="w-full rounded-xl border border-white/10 bg-slate-800/70 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-500 outline-none focus:border-sky-400"></textarea>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" wire:click="cancelReassign" class="rounded-xl border border-white/10 px-5 py-2.5 text-sm font-semibold text-slate-200 hover:bg-white/10">Cancel</button>
                    <button type="button" wire:click="confirmReassign" class="rounded-xl bg-emerald-500 px-5 py-2.5 text-sm font-semibold text-slate-950 hover:bg-emerald-400">Confirm Reassignment</button>
                </div>
            </div>
        </div>
    @endif
</div>
