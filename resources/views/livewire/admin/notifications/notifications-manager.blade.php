<div class="w-full space-y-6" wire:key="admin-notifications-manager-root">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-600">Admin</p>
            <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-teal-800">Notifications</h1>
            <p class="mt-1 text-sm text-slate-500">Every notification sent to every member. Actions here are immediate — they're the same record a member sees in their own notification bell.</p>
        </div>
        <div class="flex shrink-0 gap-2">
            <button type="button" wire:click="openBulkRead" class="inline-flex items-center px-4 py-2 bg-white text-teal-700 ring-1 ring-black/5 rounded-xl font-semibold shadow hover:bg-slate-50 transition">
                Mark Filtered Read
            </button>
            <button type="button" wire:click="openBulkDelete" class="inline-flex items-center px-4 py-2 bg-white text-rose-700 ring-1 ring-black/5 rounded-xl font-semibold shadow hover:bg-slate-50 transition">
                Delete Filtered Read
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-xl bg-green-100 text-green-800 font-semibold">{{ session('success') }}</div>
    @endif

    {{-- Stats --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <button type="button" wire:click="$set('readFilter', '')" class="rounded-2xl bg-white p-5 text-left shadow ring-1 ring-black/5 transition hover:ring-teal-300 {{ $readFilter === '' ? 'ring-2 ring-teal-500' : '' }}">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['total']) }}</p>
        </button>
        <button type="button" wire:click="$set('readFilter', 'unread')" class="rounded-2xl bg-white p-5 text-left shadow ring-1 ring-black/5 transition hover:ring-amber-300 {{ $readFilter === 'unread' ? 'ring-2 ring-amber-500' : '' }}">
            <p class="text-xs font-bold uppercase tracking-wider text-amber-500">Unread</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['unread']) }}</p>
        </button>
        <button type="button" wire:click="$set('readFilter', 'read')" class="rounded-2xl bg-white p-5 text-left shadow ring-1 ring-black/5 transition hover:ring-green-300 {{ $readFilter === 'read' ? 'ring-2 ring-green-500' : '' }}">
            <p class="text-xs font-bold uppercase tracking-wider text-green-600">Read</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['read']) }}</p>
        </button>
        <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Recipients</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['recipients']) }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="rounded-2xl bg-white p-4 shadow ring-1 ring-black/5">
        <div class="grid gap-3 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Search</label>
                <input type="text" wire:model.live.debounce.400ms="search" placeholder="Title, message, recipient…" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
            </div>
            <div class="lg:col-span-3">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Type</label>
                <select wire:model.live="typeFilter" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    <option value="">All types</option>
                    @foreach ($filterOptions['types'] as $type)
                        <option value="{{ $type }}">{{ \Illuminate\Support\Str::headline($type) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-3">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Recipient</label>
                <select wire:model.live="recipientFilter" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    <option value="">All recipients</option>
                    @foreach ($filterOptions['recipients'] as $recipient)
                        <option value="{{ $recipient->id }}">{{ $recipient->username }}{{ $recipient->trashed() ? ' (deleted)' : '' }}</option>
                    @endforeach
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
    <div class="overflow-hidden rounded-2xl bg-white shadow ring-1 ring-black/5" wire:loading.class="opacity-60" wire:target="search,typeFilter,readFilter,recipientFilter,perPage,markRead,markUnread,delete">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">
                            <button type="button" wire:click="toggleSortDir" class="inline-flex items-center gap-1 hover:text-teal-700">
                                When
                                <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span>
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Recipient</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Notification</th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider text-slate-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse ($rows as $notification)
                        @php $recipient = $recipientCache->get($notification->notifiable_id); @endphp
                        <tr class="hover:bg-slate-50 {{ $notification->read_at ? '' : 'bg-amber-50/40' }}" wire:key="notif-row-{{ $notification->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $notification->created_at->format('M d, Y g:i A') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($recipient)
                                    <p class="font-semibold text-teal-900">{{ $recipient->username }}{{ $recipient->trashed() ? ' (deleted)' : '' }}</p>
                                    <p class="text-xs text-slate-500">{{ $recipient->email }}</p>
                                @else
                                    <p class="text-sm text-slate-400">Unknown user #{{ $notification->notifiable_id }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-full bg-teal-50 px-3 py-1 text-xs font-semibold text-teal-700">{{ \App\Livewire\Admin\Notifications\NotificationsManager::typeLabel($notification) }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                <p class="font-semibold text-slate-800">{{ \App\Livewire\Admin\Notifications\NotificationsManager::dataValue($notification, 'title', '(no title)') }}</p>
                                <p class="text-xs text-slate-500">{{ \Illuminate\Support\Str::limit(\App\Livewire\Admin\Notifications\NotificationsManager::dataValue($notification, 'message', ''), 80) }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($notification->read_at)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase bg-green-100 text-green-700">Read</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase bg-amber-100 text-amber-700">Unread</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" wire:click="viewDetails('{{ $notification->id }}')" class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-50 text-slate-700 font-semibold text-xs hover:bg-slate-100">View</button>
                                    @if ($notification->read_at)
                                        <button type="button" wire:click="markUnread('{{ $notification->id }}')" class="inline-flex items-center px-3 py-1 rounded-lg bg-amber-50 text-amber-700 font-semibold text-xs hover:bg-amber-100">Mark Unread</button>
                                    @else
                                        <button type="button" wire:click="markRead('{{ $notification->id }}')" class="inline-flex items-center px-3 py-1 rounded-lg bg-emerald-50 text-emerald-700 font-semibold text-xs hover:bg-emerald-100">Mark Read</button>
                                    @endif
                                    <button type="button" wire:click="confirmDelete('{{ $notification->id }}')" class="inline-flex items-center px-3 py-1 rounded-lg bg-red-50 text-red-700 font-semibold text-xs hover:bg-red-100">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-500">No notifications match your filters.</td>
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

    {{-- Delete Confirm Modal --}}
    @if ($deletingId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4" wire:click.self="cancelDelete">
            <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl">
                <h3 class="text-lg font-bold text-teal-800">Delete this notification?</h3>
                <p class="mt-2 text-sm text-slate-500">This permanently removes it — the member will no longer see it in their notification bell. This can't be undone.</p>
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

    {{-- Bulk Mark Read Confirm Modal --}}
    @if ($showBulkReadModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4" wire:click.self="cancelBulkRead">
            <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl">
                <h3 class="text-lg font-bold text-teal-800">Mark all filtered as read?</h3>
                <p class="mt-2 text-sm text-slate-500">Marks every unread notification matching your current filters as read, for whichever member it belongs to.</p>
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" wire:click="cancelBulkRead" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="button" wire:click="bulkMarkFilteredRead" class="rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-teal-700">
                        Mark Read
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Bulk Delete Confirm Modal --}}
    @if ($showBulkDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4" wire:click.self="cancelBulkDelete">
            <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl">
                <h3 class="text-lg font-bold text-teal-800">Delete all filtered read notifications?</h3>
                <p class="mt-2 text-sm text-slate-500">Permanently deletes every already-read notification matching your current filters. This can't be undone.</p>
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" wire:click="cancelBulkDelete" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="button" wire:click="bulkDeleteFilteredRead" class="rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-rose-500">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- View Details Modal --}}
    @if ($viewingId && $viewingNotification)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4" wire:click.self="closeDetails">
            <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl sm:p-8">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-teal-600">Notification</p>
                        <h2 class="mt-1 text-xl font-extrabold text-teal-800">{{ \App\Livewire\Admin\Notifications\NotificationsManager::dataValue($viewingNotification, 'title', '(no title)') }}</h2>
                    </div>
                    <button type="button" wire:click="closeDetails" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <p class="mt-4 text-sm text-slate-700">{{ \App\Livewire\Admin\Notifications\NotificationsManager::dataValue($viewingNotification, 'message', '—') }}</p>

                @php $recipient = $recipientCache->get($viewingNotification->notifiable_id); @endphp
                <div class="mt-4 grid gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm sm:grid-cols-2">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Recipient</p>
                        <p class="text-slate-700">{{ $recipient?->username ?? 'Unknown user #'.$viewingNotification->notifiable_id }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Type</p>
                        <p class="text-slate-700">{{ \App\Livewire\Admin\Notifications\NotificationsManager::typeLabel($viewingNotification) }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sent</p>
                        <p class="text-slate-700">{{ $viewingNotification->created_at->format('M d, Y g:i:s A') }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Status</p>
                        <p class="text-slate-700">{{ $viewingNotification->read_at ? 'Read '.$viewingNotification->read_at->diffForHumans() : 'Unread' }}</p>
                    </div>
                    @if (\App\Livewire\Admin\Notifications\NotificationsManager::dataValue($viewingNotification, 'route'))
                        <div class="sm:col-span-2">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Links To</p>
                            <p class="text-slate-700">{{ \App\Livewire\Admin\Notifications\NotificationsManager::dataValue($viewingNotification, 'route') }}</p>
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
