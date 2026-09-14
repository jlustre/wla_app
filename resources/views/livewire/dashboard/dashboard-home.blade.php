<div class="space-y-6">
    <div class="grid gap-6 xl:grid-cols-[1.3fr_0.7fr]">
        <div class="rounded-[36px] border border-white/60 bg-[linear-gradient(135deg,rgba(11,23,48,0.98),rgba(17,31,61,0.94)_65%,rgba(47,111,237,0.86))] p-8 text-white shadow-[0_30px_100px_-55px_rgba(15,23,42,0.95)]">
            <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.32em] text-blue-200">Sponsor member command center</p>
                    <h2 class="mt-4 text-3xl font-bold tracking-tight sm:text-4xl">Welcome back. Manage sponsor growth, education, and member engagement from one premium workspace.</h2>
                    <p class="mt-4 text-sm leading-6 text-slate-200">Built for a compliance-first sponsor platform that keeps member growth, education, and engagement in one workspace.</p>
                </div>

                <div class="grid gap-3 rounded-[28px] border border-white/10 bg-white/5 p-5 backdrop-blur sm:grid-cols-2 lg:min-w-[24rem]">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-blue-100/80">Membership Status</p>
                        <p class="mt-2 text-lg font-bold">{{ $membership['status'] }}</p>
                        <p class="mt-1 text-sm text-slate-300">{{ $membership['tier'] }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-blue-100/80">Profile Completion</p>
                        <p class="mt-2 text-lg font-bold">{{ $membership['completion'] }}%</p>
                        <p class="mt-1 text-sm text-slate-300">Joined {{ $membership['joined'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-[36px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-blue-600 dark:text-blue-300">Upcoming Event</p>
            <h3 class="mt-3 text-2xl font-bold text-slate-950 dark:text-white">{{ $event['title'] }}</h3>
            <p class="mt-2 text-sm font-semibold text-amber-600 dark:text-amber-300">{{ $event['datetime'] }}</p>
            <p class="mt-4 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $event['description'] }}</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <x-spa-link :href="route('member.events')" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950">{{ $event['cta_label'] }}</x-spa-link>
                <x-spa-link :href="route('member.notifications')" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Open reminders</x-spa-link>
            </div>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($stats as $stat)
            <livewire:dashboard.stat-card :title="$stat['title']" :value="$stat['value']" :change="$stat['change']" :icon="$stat['icon']" :accent="$stat['accent']" :key="$stat['title']" />
        @endforeach
    </div>

    <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
        <livewire:dashboard.sponsor-card :sponsor="$sponsor" />
        <div class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-blue-600 dark:text-blue-300">Notifications Preview</p>
            <h2 class="mt-3 text-2xl font-bold text-slate-950 dark:text-white">What needs attention</h2>
            <div class="mt-6 space-y-3">
                @foreach ($notificationsPreview as $notification)
                    <div class="rounded-3xl bg-slate-50 px-4 py-4 dark:bg-slate-800/70">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $notification['title'] }}</p>
                            <span class="text-xs text-slate-400">{{ $notification['time'] }}</span>
                        </div>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $notification['message'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1fr_1fr]">
        <livewire:dashboard.invite-link-card :invite="$invite" :recent-members="$recentMembers" />
        <livewire:dashboard.training-progress-card :training="$training" />
    </div>

    <livewire:dashboard.quick-actions :actions="$quickActions" />

    <div class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
        <livewire:dashboard.recent-activity-timeline :activities="$activities" />

        <div class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-blue-600 dark:text-blue-300">Membership Snapshot</p>
            <h2 class="mt-3 text-2xl font-bold text-slate-950 dark:text-white">Member profile health</h2>

            <div class="mt-6 space-y-4">
                <div class="rounded-3xl bg-slate-50 p-4 dark:bg-slate-800/70">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Status</p>
                    <p class="mt-2 text-lg font-bold text-slate-950 dark:text-white">{{ $membership['status'] }}</p>
                </div>
                <div class="rounded-3xl bg-slate-50 p-4 dark:bg-slate-800/70">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Role</p>
                    <p class="mt-2 text-lg font-bold text-slate-950 dark:text-white">{{ \Illuminate\Support\Str::headline($membership['role']) }}</p>
                </div>
                <div class="rounded-3xl bg-slate-50 p-4 dark:bg-slate-800/70">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Invite Code</p>
                    <p class="mt-2 text-lg font-bold text-slate-950 dark:text-white">{{ $invite['short_code'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>