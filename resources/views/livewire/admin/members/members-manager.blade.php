<div class="w-full space-y-6" wire:key="admin-members-manager-root">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-600">Admin</p>
            <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-teal-800">Members Management</h1>
            <p class="mt-1 text-sm text-slate-500">Review member accounts, assign roles, and manage account status.</p>
        </div>
        <button type="button" wire:click="openCreate" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-xl font-semibold shadow hover:bg-teal-700 transition">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Member
        </button>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-xl bg-green-100 text-green-800 font-semibold">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="p-4 rounded-xl bg-red-100 text-red-800 font-semibold">{{ session('error') }}</div>
    @endif

    {{-- Stats --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <button type="button" wire:click="$set('statusFilter', '')" class="rounded-2xl bg-white p-5 text-left shadow ring-1 ring-black/5 transition hover:ring-teal-300 {{ $statusFilter === '' ? 'ring-2 ring-teal-500' : '' }}">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Members</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['total']) }}</p>
        </button>
        <button type="button" wire:click="$set('statusFilter', 'active')" class="rounded-2xl bg-white p-5 text-left shadow ring-1 ring-black/5 transition hover:ring-green-300 {{ $statusFilter === 'active' ? 'ring-2 ring-green-500' : '' }}">
            <p class="text-xs font-bold uppercase tracking-wider text-green-600">Active</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['active']) }}</p>
        </button>
        <button type="button" wire:click="$set('statusFilter', 'pending')" class="rounded-2xl bg-white p-5 text-left shadow ring-1 ring-black/5 transition hover:ring-amber-300 {{ $statusFilter === 'pending' ? 'ring-2 ring-amber-500' : '' }}">
            <p class="text-xs font-bold uppercase tracking-wider text-amber-500">Pending</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['pending']) }}</p>
        </button>
        <button type="button" wire:click="$set('statusFilter', 'suspended')" class="rounded-2xl bg-white p-5 text-left shadow ring-1 ring-black/5 transition hover:ring-rose-300 {{ $statusFilter === 'suspended' ? 'ring-2 ring-rose-500' : '' }}">
            <p class="text-xs font-bold uppercase tracking-wider text-rose-500">Suspended</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['suspended']) }}</p>
        </button>
        <button type="button" wire:click="$set('statusFilter', 'trashed')" class="rounded-2xl bg-white p-5 text-left shadow ring-1 ring-black/5 transition hover:ring-slate-300 {{ $statusFilter === 'trashed' ? 'ring-2 ring-slate-500' : '' }}">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Deleted</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['trashed']) }}</p>
        </button>
    </div>

    {{-- Filters --}}
    <div class="rounded-2xl bg-white p-4 shadow ring-1 ring-black/5">
        <div class="grid gap-3 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Search</label>
                <input type="text" wire:model.live.debounce.400ms="search" placeholder="Username or email…" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
            </div>
            <div class="lg:col-span-3">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Status</label>
                <select wire:model.live="statusFilter" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    <option value="">All</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="pending">Pending</option>
                    <option value="suspended">Suspended</option>
                    <option value="trashed">Deleted</option>
                </select>
            </div>
            <div class="lg:col-span-3">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Role</label>
                <select wire:model.live="roleFilter" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    <option value="">All roles</option>
                    @foreach ($filterOptions['roles'] as $roleName)
                        <option value="{{ $roleName }}">{{ $roleName }}</option>
                    @endforeach
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
    <div class="overflow-hidden rounded-2xl bg-white shadow ring-1 ring-black/5" wire:loading.class="opacity-60" wire:target="search,statusFilter,roleFilter,perPage,delete,restore">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">
                            <button type="button" wire:click="toggleSort('username')" class="inline-flex items-center gap-1 hover:text-teal-700">
                                Member
                                @if ($sortBy === 'username') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">
                            <button type="button" wire:click="toggleSort('status')" class="inline-flex items-center gap-1 hover:text-teal-700">
                                Status
                                @if ($sortBy === 'status') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Sponsor</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">
                            <button type="button" wire:click="toggleSort('direct_members_count')" class="inline-flex items-center gap-1 hover:text-teal-700">
                                Referrals
                                @if ($sortBy === 'direct_members_count') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">
                            <button type="button" wire:click="toggleSort('created_at')" class="inline-flex items-center gap-1 hover:text-teal-700">
                                Joined
                                @if ($sortBy === 'created_at') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider text-slate-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse ($rows as $member)
                        <tr class="hover:bg-slate-50 {{ $member->trashed() ? 'opacity-60' : '' }}" wire:key="member-row-{{ $member->id }}">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-teal-900">{{ $member->username }}</p>
                                <p class="text-xs text-slate-500">{{ $member->email }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($member->trashed())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase bg-slate-200 text-slate-600">Deleted</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase {{ $member->status->badgeClass() }}">{{ $member->status->label() }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $member->getRoleNames()->join(', ') ?: 'member' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $member->sponsor?->username ?? 'Unassigned' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $member->direct_members_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $member->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" wire:click="viewDetails({{ $member->id }})" class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-50 text-slate-700 font-semibold text-xs hover:bg-slate-100">View</button>
                                    @if ($member->trashed())
                                        <button type="button" wire:click="restore({{ $member->id }})" class="inline-flex items-center px-3 py-1 rounded-lg bg-green-50 text-green-700 font-semibold text-xs hover:bg-green-100">Restore</button>
                                    @else
                                        <button type="button" wire:click="openEdit({{ $member->id }})" class="inline-flex items-center px-3 py-1 rounded-lg bg-emerald-50 text-emerald-700 font-semibold text-xs hover:bg-emerald-100">Edit</button>
                                        <button type="button" wire:click="openResetPassword({{ $member->id }})" class="inline-flex items-center px-3 py-1 rounded-lg bg-amber-50 text-amber-700 font-semibold text-xs hover:bg-amber-100">Reset Password</button>
                                        @if ($member->id !== auth()->id())
                                            <button type="button" wire:click="confirmDelete({{ $member->id }})" class="inline-flex items-center px-3 py-1 rounded-lg bg-red-50 text-red-700 font-semibold text-xs hover:bg-red-100">Delete</button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-sm text-slate-500">No members match your filters.</td>
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
                    <h2 class="text-xl font-extrabold text-teal-800">{{ $editingId ? 'Edit Member' : 'Add Member' }}</h2>
                    <button type="button" wire:click="closeModal" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="mt-6 space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Username</label>
                            <input type="text" wire:model="username" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                            @error('username') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Email</label>
                            <input type="email" wire:model="email" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                            @error('email') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>

                        @unless ($editingId)
                            <div>
                                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Password</label>
                                <input type="password" wire:model="password" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                                @error('password') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Confirm Password</label>
                                <input type="password" wire:model="password_confirmation" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Sponsor (optional)</label>
                                <select wire:model="sponsor_id" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                                    <option value="">No sponsor</option>
                                    @foreach ($sponsorOptions as $sponsor)
                                        <option value="{{ $sponsor->id }}">{{ $sponsor->username }}</option>
                                    @endforeach
                                </select>
                                @error('sponsor_id') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        @else
                            <div class="sm:col-span-2">
                                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Sponsor</label>
                                <p class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-600">
                                    {{ $sponsorOptions->firstWhere('id', (int) $sponsor_id)?->username ?? 'Unassigned' }}
                                    &mdash; <a href="{{ route('admin.downline') }}" wire:navigate class="font-semibold text-teal-700 hover:underline">reassign in Downline Tree</a>
                                </p>
                            </div>
                        @endunless

                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Status</label>
                            <select wire:model="status" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="pending">Pending</option>
                                <option value="suspended">Suspended</option>
                            </select>
                            @error('status') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Role</label>
                            <select wire:model="role" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                                @foreach ($assignableRoles as $roleName)
                                    <option value="{{ $roleName }}">{{ $roleName }}</option>
                                @endforeach
                            </select>
                            @error('role') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-5">
                        <button type="button" wire:click="closeModal" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                            Cancel
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="save" class="rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-teal-700 disabled:opacity-60">
                            <span wire:loading.remove wire:target="save">{{ $editingId ? 'Save Changes' : 'Add Member' }}</span>
                            <span wire:loading wire:target="save">Saving…</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Reset Password Modal --}}
    @if ($resettingId && $resettingUser)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4" wire:click.self="cancelResetPassword">
            <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl">
                <h3 class="text-lg font-bold text-teal-800">Reset Password &mdash; {{ $resettingUser->username }}</h3>
                <form wire:submit.prevent="saveResetPassword" class="mt-4 space-y-4">
                    <div>
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">New Password</label>
                        <input type="password" wire:model="newPassword" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                        @error('newPassword') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Confirm Password</label>
                        <input type="password" wire:model="newPassword_confirmation" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    </div>
                    <div class="flex items-center justify-end gap-3">
                        <button type="button" wire:click="cancelResetPassword" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                            Cancel
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="saveResetPassword" class="rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-teal-700 disabled:opacity-60">
                            <span wire:loading.remove wire:target="saveResetPassword">Save</span>
                            <span wire:loading wire:target="saveResetPassword">Saving…</span>
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
                <h3 class="text-lg font-bold text-teal-800">Delete this member?</h3>
                <p class="mt-2 text-sm text-slate-500">Their account will be soft-deleted and can be restored later from the Deleted filter.</p>
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

    {{-- View Details Modal --}}
    @if ($viewingId && $viewingUser)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4" wire:click.self="closeDetails">
            <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl sm:p-8">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-teal-600">Member Details</p>
                        <h2 class="mt-1 text-xl font-extrabold text-teal-800">{{ $viewingUser->username }}</h2>
                    </div>
                    <button type="button" wire:click="closeDetails" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="mt-5 grid gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm sm:grid-cols-2">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Email</p>
                        <p class="text-slate-700">{{ $viewingUser->email }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Status</p>
                        <p class="text-slate-700">{{ $viewingUser->trashed() ? 'Deleted' : $viewingUser->status->label() }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Role</p>
                        <p class="text-slate-700">{{ $viewingUser->getRoleNames()->join(', ') ?: 'member' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sponsor</p>
                        <p class="text-slate-700">{{ $viewingUser->sponsor?->username ?? 'Unassigned' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Direct Referrals</p>
                        <p class="text-slate-700">{{ $viewingUser->direct_members_count }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Company Affiliations</p>
                        <p class="text-slate-700">{{ $viewingUser->company_affiliations_count }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Joined</p>
                        <p class="text-slate-700">{{ $viewingUser->created_at->format('M d, Y') }}</p>
                    </div>
                    @if ($viewingUser->profile?->city)
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">City</p>
                            <p class="text-slate-700">{{ $viewingUser->profile->city }}</p>
                        </div>
                    @endif
                    @if ($viewingUser->profile?->phone_number)
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Phone</p>
                            <p class="text-slate-700">{{ $viewingUser->profile->phone_number }}</p>
                        </div>
                    @endif
                    @if ($viewingUser->profile?->bio)
                        <div class="sm:col-span-2">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Bio</p>
                            <p class="text-slate-700">{{ $viewingUser->profile->bio }}</p>
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
