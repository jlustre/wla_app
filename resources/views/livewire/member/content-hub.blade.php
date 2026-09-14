<div class="space-y-6">
    @if ($statusMessage)
        <div class="rounded-[24px] border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-200">
            {{ $statusMessage }}
        </div>
    @endif

    @if ($metrics)
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($metrics as $metric)
                <div class="rounded-[28px] border border-slate-200/80 bg-white/90 p-5 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">{{ $metric['label'] }}</p>
                    <p class="mt-3 text-2xl font-bold text-slate-950 dark:text-white">{{ $metric['value'] }}</p>
                </div>
            @endforeach
        </div>
    @endif

    @if ($page === 'invite-link')
        <div class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-950 dark:text-white">Invite actions</h2>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Copy your share link or resend a pending invite without leaving the member workspace.</p>
                </div>
                <button type="button" wire:click="copyInviteLink" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950">Copy invite link</button>
            </div>
            <div class="mt-5 space-y-3">
                @foreach ($inviteActions as $invite)
                    <div class="flex flex-col gap-3 rounded-3xl bg-slate-50 px-4 py-4 dark:bg-slate-800/70 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $invite['label'] }}</p>
                            <p class="mt-1 text-xs uppercase tracking-[0.24em] text-slate-400">{{ $invite['status'] }} • {{ $invite['sent'] }}</p>
                        </div>
                        @if ($invite['can_resend'])
                            <button type="button" wire:click="resendInvite({{ $invite['id'] }})" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Resend invite</button>
                        @else
                            <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-300">Accepted</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if ($page === 'notifications' && $notificationActions)
        <div class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
            <h2 class="text-xl font-bold text-slate-950 dark:text-white">Unread actions</h2>
            <div class="mt-5 space-y-3">
                @foreach ($notificationActions as $notification)
                    <div class="flex flex-col gap-3 rounded-3xl bg-slate-50 px-4 py-4 dark:bg-slate-800/70 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $notification['title'] }}</p>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $notification['message'] }}</p>
                            <p class="mt-2 text-xs uppercase tracking-[0.24em] text-slate-400">{{ $notification['type'] }}</p>
                        </div>
                        <button type="button" wire:click="markNotificationAsRead('{{ $notification['id'] }}')" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Mark read</button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if ($page === 'events' && $eventActions)
        <div class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
            <h2 class="text-xl font-bold text-slate-950 dark:text-white">Reserve events</h2>
            <div class="mt-5 grid gap-4 lg:grid-cols-2">
                @foreach ($eventActions as $event)
                    <article class="rounded-3xl bg-slate-50 p-5 dark:bg-slate-800/70">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-blue-600 dark:text-blue-300">{{ $event['status'] }}</p>
                        <h3 class="mt-3 text-lg font-bold text-slate-950 dark:text-white">{{ $event['title'] }}</h3>
                        <p class="mt-2 text-sm font-semibold text-amber-600 dark:text-amber-300">{{ $event['starts_at'] }}</p>
                        <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">{{ $event['description'] }}</p>
                        <div class="mt-4">
                            @if ($event['can_reserve'])
                                <button type="button" wire:click="reserveEvent({{ $event['id'] }})" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950">Reserve seat</button>
                            @else
                                <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-300">Already reserved</span>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    @endif

    @if ($page === 'resources' && $resourceActions)
        <div class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
            <h2 class="text-xl font-bold text-slate-950 dark:text-white">Resource actions</h2>
            <div class="mt-5 grid gap-4 lg:grid-cols-2">
                @foreach ($resourceActions as $resource)
                    <article class="rounded-3xl bg-slate-50 p-5 dark:bg-slate-800/70">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-blue-600 dark:text-blue-300">{{ $resource['category'] }} • {{ strtoupper($resource['status']) }}</p>
                        <h3 class="mt-3 text-lg font-bold text-slate-950 dark:text-white">{{ $resource['title'] }}</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Format: {{ strtoupper($resource['format']) }} • Reviewed {{ $resource['view_count'] }} times</p>
                        <div class="mt-4 flex flex-wrap gap-3">
                            <button type="button" wire:click="markResourceReviewed({{ $resource['id'] }})" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Mark reviewed</button>
                            @if ($resource['url'])
                                <a href="{{ $resource['url'] }}" target="_blank" rel="noreferrer" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950">Open resource</a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    @endif

    @if ($primaryCards || $secondaryCards)
        <div class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
            <div class="space-y-6">
                @foreach ($primaryCards as $card)
                    <article class="rounded-[28px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
                        <h2 class="text-xl font-bold text-slate-950 dark:text-white">{{ $card['title'] }}</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $card['body'] }}</p>
                        @if (! empty($card['meta']))
                            <p class="mt-3 text-xs font-semibold uppercase tracking-[0.24em] text-blue-600 dark:text-blue-300">{{ $card['meta'] }}</p>
                        @endif
                    </article>
                @endforeach
            </div>

            <div class="space-y-6">
                @foreach ($secondaryCards as $card)
                    <article class="rounded-[28px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
                        <h2 class="text-xl font-bold text-slate-950 dark:text-white">{{ $card['title'] }}</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $card['body'] }}</p>
                        @if (! empty($card['meta']))
                            <p class="mt-3 text-xs font-semibold uppercase tracking-[0.24em] text-blue-600 dark:text-blue-300">{{ $card['meta'] }}</p>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    @endif

    @if (! empty($table['rows']))
        <div class="overflow-hidden rounded-[32px] border border-slate-200/80 bg-white/90 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
            <div class="border-b border-slate-200/80 px-6 py-5 dark:border-slate-800">
                <h2 class="text-xl font-bold text-slate-950 dark:text-white">{{ $title }}</h2>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $description }}</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                    <thead class="bg-slate-50/80 dark:bg-slate-800/70">
                        <tr>
                            @foreach ($table['columns'] as $column)
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">{{ $column }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/80 dark:divide-slate-800">
                        @foreach ($table['rows'] as $row)
                            <tr>
                                @foreach ($row as $cell)
                                    <td class="px-6 py-4 text-sm text-slate-700 dark:text-slate-200">{{ $cell }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if ($timeline)
        <div class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
            <h2 class="text-xl font-bold text-slate-950 dark:text-white">Recent activity</h2>
            <div class="mt-5 space-y-4">
                @foreach ($timeline as $item)
                    <div class="rounded-3xl bg-slate-50 px-4 py-4 dark:bg-slate-800/70">
                        <div class="flex items-center justify-between gap-4">
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $item['title'] }}</p>
                            <span class="text-xs text-slate-400">{{ $item['time'] }}</span>
                        </div>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $item['body'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>