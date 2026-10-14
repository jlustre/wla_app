<div class="rounded-[20px] border border-slate-200/80 bg-white/90 p-4 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
    <div class="flex items-center justify-between gap-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-600 dark:text-emerald-300">Recent Activity</p>
            <h2 class="mt-2 text-xl font-bold text-slate-950 dark:text-white">Timeline</h2>
        </div>
        <x-spa-link :href="route('member.notifications')" class="text-sm font-semibold text-emerald-600 dark:text-emerald-300">See all</x-spa-link>
    </div>

    <div class="mt-3 space-y-3">
        @forelse ($activities as $activity)
            <div class="flex gap-3">
                <div class="flex flex-col items-center">
                    <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                        <x-dashboard-icon name="clock" class="h-4 w-4" />
                    </span>
                    <span class="mt-2 h-full w-px bg-slate-200 dark:bg-slate-800"></span>
                </div>
                <div class="min-w-0 flex-1 pb-3">
                    <div class="flex items-center justify-between gap-3">
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">{{ $activity['title'] }}</h3>
                        <span class="text-xs text-slate-400">{{ $activity['time'] }}</span>
                    </div>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $activity['description'] }}</p>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-200 px-6 py-8 text-center dark:border-slate-700">
                <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">No recent activity</p>
            </div>
        @endforelse
    </div>
</div>