<div class="w-full space-y-6" wire:key="admin-roles-manager-root">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-600">Admin</p>
            <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-teal-800">Role Management</h1>
            <p class="mt-1 text-sm text-slate-500">Review platform roles, who's assigned to each, and manage permissions.</p>
        </div>
        <button type="button" wire:click="openCreate" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-xl font-semibold shadow hover:bg-teal-700 transition">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Role
        </button>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-xl bg-green-100 text-green-800 font-semibold">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="p-4 rounded-xl bg-red-100 text-red-800 font-semibold">{{ session('error') }}</div>
    @endif

    {{-- Stats --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <button type="button" wire:click="$set('statusFilter', '')" class="rounded-2xl bg-white p-5 text-left shadow ring-1 ring-black/5 transition hover:ring-teal-300 {{ $statusFilter === '' ? 'ring-2 ring-teal-500' : '' }}">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Roles</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['total_roles']) }}</p>
        </button>
        <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
            <p class="text-xs font-bold uppercase tracking-wider text-teal-600">Total Assignments</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['total_assignments']) }}</p>
        </div>
        <button type="button" wire:click="$set('statusFilter', 'unused')" class="rounded-2xl bg-white p-5 text-left shadow ring-1 ring-black/5 transition hover:ring-amber-300 {{ $statusFilter === 'unused' ? 'ring-2 ring-amber-500' : '' }}">
            <p class="text-xs font-bold uppercase tracking-wider text-amber-500">Unused Roles</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['unused_roles']) }}</p>
        </button>
        <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Permissions Defined</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['total_permissions']) }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="rounded-2xl bg-white p-4 shadow ring-1 ring-black/5">
        <div class="grid gap-3 lg:grid-cols-12">
            <div class="lg:col-span-6">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Search</label>
                <input type="text" wire:model.live.debounce.400ms="search" placeholder="Role name…" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
            </div>
            <div class="lg:col-span-4">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Status</label>
                <select wire:model.live="statusFilter" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    <option value="">All roles</option>
                    <option value="unused">Unused (0 members)</option>
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
    <div class="overflow-hidden rounded-2xl bg-white shadow ring-1 ring-black/5" wire:loading.class="opacity-60" wire:target="search,statusFilter,perPage,delete">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">
                            <button type="button" wire:click="toggleSort('name')" class="inline-flex items-center gap-1 hover:text-teal-700">
                                Role
                                @if ($sortBy === 'name') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Guard</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Permissions</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">
                            <button type="button" wire:click="toggleSort('users_count')" class="inline-flex items-center gap-1 hover:text-teal-700">
                                Members
                                @if ($sortBy === 'users_count') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider text-slate-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse ($rows as $role)
                        <tr class="hover:bg-slate-50" wire:key="role-row-{{ $role->id }}">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-teal-900">{{ $role->name }}</p>
                                @if (in_array($role->name, $protectedRoles, true))
                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Protected</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $role->guard_name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $role->permissions_count ?? $role->permissions()->count() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.users.index', ['roleFilter' => $role->name]) }}" wire:navigate class="inline-flex items-center rounded-full bg-teal-50 px-3 py-1 text-xs font-bold text-teal-700 hover:bg-teal-100">
                                    {{ $role->users_count }} {{ \Illuminate\Support\Str::plural('member', $role->users_count) }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" wire:click="viewDetails({{ $role->id }})" class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-50 text-slate-700 font-semibold text-xs hover:bg-slate-100">View</button>
                                    <button type="button" wire:click="openEdit({{ $role->id }})" class="inline-flex items-center px-3 py-1 rounded-lg bg-emerald-50 text-emerald-700 font-semibold text-xs hover:bg-emerald-100">Edit</button>
                                    @unless (in_array($role->name, $protectedRoles, true))
                                        <button type="button" wire:click="confirmDelete({{ $role->id }})" class="inline-flex items-center px-3 py-1 rounded-lg bg-red-50 text-red-700 font-semibold text-xs hover:bg-red-100">Delete</button>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500">No roles match your filters.</td>
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
                    <h2 class="text-xl font-extrabold text-teal-800">{{ $editingId ? 'Edit Role' : 'Add Role' }}</h2>
                    <button type="button" wire:click="closeModal" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                @if ($editingId && in_array($name, $protectedRoles, true))
                    <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs font-semibold text-amber-700">
                        This role's name is relied on by the app's access rules and can't be changed, but you can still edit its permissions.
                    </div>
                @endif

                <form wire:submit.prevent="save" class="mt-6 space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Name</label>
                            <input type="text" wire:model="name" @disabled($editingId && in_array($name, $protectedRoles, true)) class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 disabled:bg-slate-100 disabled:text-slate-400">
                            @error('name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Guard</label>
                            <input type="text" wire:model="guard_name" @disabled($editingId) class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 disabled:bg-slate-100 disabled:text-slate-400">
                            @error('guard_name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Permissions (comma-separated)</label>
                            <textarea wire:model="permissionsInput" rows="3" placeholder="e.g. manage-companies, view-reports" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100"></textarea>
                            <p class="mt-1 text-xs text-slate-400">New permission names are created automatically. Leave blank for no granular permissions.</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-5">
                        <button type="button" wire:click="closeModal" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                            Cancel
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="save" class="rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-teal-700 disabled:opacity-60">
                            <span wire:loading.remove wire:target="save">{{ $editingId ? 'Save Changes' : 'Add Role' }}</span>
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
                <h3 class="text-lg font-bold text-teal-800">Delete this role?</h3>
                <p class="mt-2 text-sm text-slate-500">This can't be undone. Make sure no members depend on it.</p>
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
    @if ($viewingId && $viewingRole)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4" wire:click.self="closeDetails">
            <div class="max-h-[90vh] w-full max-w-xl overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl sm:p-8">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-teal-600">Role Details</p>
                        <h2 class="mt-1 text-xl font-extrabold text-teal-800">{{ $viewingRole->name }}</h2>
                    </div>
                    <button type="button" wire:click="closeDetails" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="mt-5">
                    <p class="mb-2 text-xs font-bold uppercase tracking-wider text-slate-500">Permissions ({{ $viewingRole->permissions->count() }})</p>
                    @if ($viewingRole->permissions->isEmpty())
                        <p class="text-sm text-slate-400">No granular permissions assigned.</p>
                    @else
                        <div class="flex flex-wrap gap-2">
                            @foreach ($viewingRole->permissions as $permission)
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $permission->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="mt-6">
                    <p class="mb-2 text-xs font-bold uppercase tracking-wider text-slate-500">Members ({{ $viewingRole->users_count }})</p>
                    @if ($viewingRole->users->isEmpty())
                        <p class="text-sm text-slate-400">No members currently have this role.</p>
                    @else
                        <ul class="divide-y divide-slate-100 rounded-xl border border-slate-200">
                            @foreach ($viewingRole->users as $user)
                                <li class="flex items-center justify-between px-4 py-2 text-sm">
                                    <span class="font-semibold text-slate-800">{{ $user->username }}</span>
                                    <span class="text-slate-500">{{ $user->email }}</span>
                                </li>
                            @endforeach
                        </ul>
                        @if ($viewingRole->users_count > $viewingRole->users->count())
                            <p class="mt-2 text-xs text-slate-400">Showing {{ $viewingRole->users->count() }} of {{ $viewingRole->users_count }}. <a href="{{ route('admin.users.index', ['roleFilter' => $viewingRole->name]) }}" wire:navigate class="font-semibold text-teal-700 hover:underline">View all in Members Management</a>.</p>
                        @endif
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
