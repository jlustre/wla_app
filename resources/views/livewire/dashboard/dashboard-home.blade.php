<div class="space-y-4">
    <div class="grid gap-4 xl:grid-cols-[1.3fr_0.7fr]">
        <div class="rounded-[24px] border border-white/60 wla-hero p-5 text-white shadow-[0_30px_100px_-55px_rgba(15,23,42,0.95)]">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold uppercase tracking-[0.32em] text-emerald-300">Member command center</p>
                    <h2 class="mt-2 text-2xl font-bold tracking-tight sm:text-3xl">Welcome back. Manage sponsor growth, education, and member engagement from one premium workspace.</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-200">Built for a compliance-first sponsor platform that keeps member growth, education, and engagement in one workspace.</p>
                </div>

                <div class="grid shrink-0 grid-cols-2 gap-3 rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur lg:w-72">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-emerald-100/80">Membership Status</p>
                        <p class="mt-1 text-lg font-bold">{{ $membership['status'] }}</p>
                        <p class="mt-1 text-sm text-slate-300">{{ $membership['tier'] }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-emerald-100/80">Profile Completion</p>
                        <p class="mt-1 text-lg font-bold">{{ $membership['completion'] }}%</p>
                        <p class="mt-1 text-sm text-slate-300">Joined {{ $membership['joined'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-[24px] border border-slate-200/80 bg-white/90 p-4 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-600 dark:text-emerald-300">Upcoming Event</p>
            <h3 class="mt-2 text-xl font-bold text-slate-950 dark:text-white">{{ $event['title'] }}</h3>
            <p class="mt-1 text-sm font-semibold text-yellow-600 dark:text-yellow-300">{{ $event['datetime'] }}</p>
            <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $event['description'] }}</p>
            <div class="mt-4 flex flex-wrap gap-2">
                <x-spa-link :href="route('member.events')" class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-500">{{ $event['cta_label'] }}</x-spa-link>
                <x-spa-link :href="route('member.notifications')" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">Open reminders</x-spa-link>
            </div>
        </div>
    </div>

    <livewire:member.onboarding-checklist :compact="true" />

    @include('livewire.dashboard.partials.business-quick-links')

    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($stats as $stat)
            <livewire:dashboard.stat-card :title="$stat['title']" :value="$stat['value']" :change="$stat['change']" :icon="$stat['icon']" :accent="$stat['accent']" :key="$stat['title']" />
        @endforeach
    </div>

    <div class="grid gap-4 xl:grid-cols-[1.1fr_0.9fr]">
        <livewire:dashboard.sponsor-card :sponsor="$sponsor" />
        <div class="rounded-[20px] border border-slate-200/80 bg-white/90 p-4 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-600 dark:text-emerald-300">Notifications Preview</p>
            <h2 class="mt-2 text-xl font-bold text-slate-950 dark:text-white">What needs attention</h2>
            <div class="mt-3 space-y-2">
                @foreach ($notificationsPreview as $notification)
                    <div class="rounded-2xl bg-slate-50 px-3 py-3 dark:bg-slate-800/70">
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

    <div class="grid gap-4 xl:grid-cols-[1fr_1fr]">
        <livewire:dashboard.invite-link-card :invite="$invite" :recent-members="$recentMembers" />
        <livewire:dashboard.training-progress-card :training="$training" />
    </div>

    <livewire:dashboard.quick-actions :actions="$quickActions" />

    <div class="grid gap-4 xl:grid-cols-[1.15fr_0.85fr]">
        <livewire:dashboard.recent-activity-timeline :activities="$activities" />

        <div class="rounded-[20px] border border-slate-200/80 bg-white/90 p-4 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-600 dark:text-emerald-300">Membership Snapshot</p>
            <h2 class="mt-2 text-xl font-bold text-slate-950 dark:text-white">Member profile health</h2>

            <div class="mt-3 space-y-2">
                <div class="rounded-2xl bg-slate-50 p-3 dark:bg-slate-800/70">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Status</p>
                    <p class="mt-1 text-lg font-bold text-slate-950 dark:text-white">{{ $membership['status'] }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-3 dark:bg-slate-800/70">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Role</p>
                    <p class="mt-1 text-lg font-bold text-slate-950 dark:text-white">{{ \Illuminate\Support\Str::headline($membership['role']) }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-3 dark:bg-slate-800/70">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Invite Code</p>
                    <p class="mt-1 text-lg font-bold text-slate-950 dark:text-white">{{ $invite['short_code'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>