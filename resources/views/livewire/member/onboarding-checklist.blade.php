<div>
@if ($compact)
    {{-- Dashboard widget: hides itself once the checklist is fully complete --}}
    @unless ($progress['isComplete'])
        <div class="rounded-[24px] border border-slate-200/80 bg-white/90 p-5 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-600 dark:text-emerald-300">Getting Started</p>
                    <h2 class="mt-1 text-lg font-bold text-slate-950 dark:text-white">Your Onboarding Checklist</h2>
                </div>
                <x-spa-link :href="\App\Support\Nav::route('member.onboarding')" class="inline-flex items-center gap-1 text-sm font-semibold text-emerald-600 hover:text-emerald-700 dark:text-emerald-300 dark:hover:text-emerald-200">
                    View full checklist
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6" /></svg>
                </x-spa-link>
            </div>

            <div class="mt-4 flex items-center gap-3">
                <div class="h-2.5 flex-1 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                    <div class="h-2.5 rounded-full bg-gradient-to-r from-emerald-400 to-emerald-600 transition-all" style="width: {{ $progress['percent'] }}%"></div>
                </div>
                <span class="shrink-0 text-sm font-semibold text-slate-600 dark:text-slate-300">{{ $progress['completed'] }}/{{ $progress['total'] }}</span>
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
                @foreach (collect($progress['steps'])->where('completed', false)->take(3) as $step)
                    <x-spa-link :href="\App\Support\Nav::route($step['route'])" class="group inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 py-1.5 pl-1.5 pr-3 text-xs font-semibold text-slate-700 transition hover:border-emerald-400/60 hover:bg-emerald-50 hover:text-emerald-700 dark:border-slate-700 dark:bg-slate-800/70 dark:text-slate-200 dark:hover:border-emerald-400/40 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300">
                        <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-white text-slate-500 transition group-hover:bg-emerald-100 group-hover:text-emerald-700 dark:bg-slate-900 dark:text-slate-400 dark:group-hover:bg-emerald-500/20 dark:group-hover:text-emerald-300">
                            <x-dashboard-icon :name="$step['icon']" class="h-3 w-3" />
                        </span>
                        {{ $step['title'] }}
                    </x-spa-link>
                @endforeach
            </div>
        </div>
    @endunless
@else
    {{-- Full "My Onboarding" page --}}
    <div class="space-y-6">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-600 dark:text-emerald-300">Workspace</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950 dark:text-white sm:text-3xl">My Onboarding</h1>
                <p class="mt-2 max-w-2xl text-sm text-slate-500 dark:text-slate-400">A handful of quick steps to get your profile, pipeline, and calendar ready. We'll notify you as you check each one off.</p>
            </div>
        </div>

        @if ($progress['isComplete'])
            <div class="rounded-[24px] border border-emerald-200 bg-emerald-50 p-6 dark:border-emerald-500/20 dark:bg-emerald-500/10">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-500 text-white">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-emerald-800 dark:text-emerald-200">You're fully onboarded! 🎉</h2>
                        <p class="mt-1 text-sm text-emerald-700 dark:text-emerald-300">You've completed every step. Keep building your pipeline and growing your network.</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Progress overview --}}
        <div class="rounded-[28px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Overall progress</p>
                    <p class="mt-1 text-3xl font-extrabold text-slate-950 dark:text-white">{{ $progress['percent'] }}%</p>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $progress['completed'] }} of {{ $progress['total'] }} steps complete</p>
            </div>
            <div class="mt-4 h-3 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                <div class="h-3 rounded-full bg-gradient-to-r from-emerald-400 to-emerald-600 transition-all" style="width: {{ $progress['percent'] }}%"></div>
            </div>
        </div>

        {{-- Calendar sync note --}}
        <div class="rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-800 dark:border-sky-500/20 dark:bg-sky-500/10 dark:text-sky-200">
            <div class="flex items-start gap-2.5">
                <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3.75" y="5.25" width="16.5" height="15" rx="2.25" /><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 3.75v3M16.5 3.75v3M3.75 9.75h16.5" /></svg>
                <span>
                    We added two reminders to <x-spa-link :href="\App\Support\Nav::route('member.calendar')" class="font-semibold underline hover:no-underline">My Calendar</x-spa-link> for you: finishing this checklist within a week, and a 30-day check-in with your sponsor.
                </span>
            </div>
        </div>

        {{-- Steps --}}
        <div class="space-y-3">
            @foreach ($progress['steps'] as $step)
                <div class="flex items-center gap-4 rounded-[24px] border p-5 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur transition {{ $step['completed'] ? 'border-emerald-200/80 bg-emerald-50/60 dark:border-emerald-500/20 dark:bg-emerald-500/5' : 'border-slate-200/80 bg-white/90 dark:border-slate-800 dark:bg-slate-900/85' }}">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $step['completed'] ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500' }}">
                        @if ($step['completed'])
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                        @else
                            <x-dashboard-icon :name="$step['icon']" class="h-5 w-5" />
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-base font-bold {{ $step['completed'] ? 'text-emerald-800 dark:text-emerald-200 line-through decoration-emerald-400/60' : 'text-slate-950 dark:text-white' }}">{{ $step['title'] }}</h3>
                        <p class="mt-0.5 text-sm {{ $step['completed'] ? 'text-emerald-700/80 dark:text-emerald-300/70' : 'text-slate-500 dark:text-slate-400' }}">{{ $step['description'] }}</p>
                    </div>
                    <div class="shrink-0">
                        @if ($step['completed'])
                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300">Done</span>
                        @else
                            <x-spa-link :href="\App\Support\Nav::route($step['route'])" class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200">
                                Go
                            </x-spa-link>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
</div>
