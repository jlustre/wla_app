<div class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-blue-600 dark:text-blue-300">Member Education</p>
            <h2 class="mt-3 text-2xl font-bold text-slate-950 dark:text-white">Progress and curriculum</h2>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Build confidence with a structured onboarding and education path.</p>
        </div>
        <span class="rounded-full bg-emerald-50 px-3 py-1 text-sm font-semibold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">{{ $training['completion'] }}% complete</span>
    </div>

    <div class="mt-6 h-3 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
        <div class="h-full rounded-full bg-gradient-to-r from-blue-600 via-cyan-500 to-amber-400" style="width: {{ $training['completion'] }}%"></div>
    </div>

    <div class="mt-6 space-y-3">
        @foreach ($training['modules'] as $module)
            <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3 dark:bg-slate-800/70">
                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $module['title'] }}</p>
                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ in_array($module['status'], ['Completed'], true) ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' : 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-200' }}">{{ $module['status'] }}</span>
            </div>
        @endforeach
    </div>
</div>