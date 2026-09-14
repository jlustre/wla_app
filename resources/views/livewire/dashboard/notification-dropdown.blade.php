<div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false" x-on:livewire:navigated.window="open = false">
    <button type="button" class="relative inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:border-blue-300 hover:text-blue-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300" @click="open = ! open">
        <span class="sr-only">Open notifications</span>
        <x-dashboard-icon name="bell" class="h-5 w-5" />
        @if ($unreadCount > 0)
            <span class="absolute right-2 top-2 inline-flex min-h-5 min-w-5 items-center justify-center rounded-full bg-amber-400 px-1.5 text-[11px] font-bold text-slate-950">{{ $unreadCount }}</span>
        @endif
    </button>

    <div x-cloak x-show="open" x-transition.origin.top.right @click.outside="open = false" class="absolute right-0 z-50 mt-3 w-[22rem] overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_32px_90px_-45px_rgba(15,23,42,0.6)] dark:border-slate-800 dark:bg-slate-900">
        <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-900 dark:text-white">Notifications</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Member, sponsor, training, and system updates.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $unreadCount }} unread</span>
                    @if ($unreadCount > 0)
                        <button type="button" wire:click="markAllAsRead" class="text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-300">Mark all read</button>
                    @endif
                </div>
            </div>
        </div>

        <div class="max-h-[26rem] overflow-y-auto px-3 py-3">
            @forelse ($notifications as $notification)
                <div class="rounded-2xl border border-transparent px-3 py-3 transition hover:border-slate-200 hover:bg-slate-50 dark:hover:border-slate-800 dark:hover:bg-slate-800/70">
                    <div class="flex items-start gap-3">
                        <span class="mt-1 inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-300">
                            <x-dashboard-icon name="bell" class="h-4 w-4" />
                        </span>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-3">
                                <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $notification['title'] }}</p>
                                <span class="text-xs text-slate-400">{{ $notification['time'] }}</span>
                            </div>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $notification['message'] }}</p>
                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400">{{ $notification['type'] }}</span>
                                @if (! $notification['read_at'])
                                    <button type="button" wire:click="markAsRead('{{ $notification['id'] }}')" class="text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-300">Mark as read</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-200 px-6 py-12 text-center dark:border-slate-800">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">No notifications right now</p>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">New sponsor messages and reminders will appear here.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>