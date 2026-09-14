<div class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-blue-600 dark:text-blue-300">My Sponsor</p>
            <h2 class="mt-3 text-2xl font-bold text-slate-950 dark:text-white">{{ $sponsor['name'] }}</h2>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Sponsor code {{ $sponsor['code'] }}</p>
        </div>
        <livewire:dashboard.status-badge :label="$sponsor['status']" tone="emerald" />
    </div>

    <div class="mt-6 grid gap-4 sm:grid-cols-2">
        <div class="rounded-3xl bg-slate-50 p-4 dark:bg-slate-800/70">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Joined</p>
            <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-white">{{ $sponsor['joined_date'] }}</p>
        </div>
        <div class="rounded-3xl bg-slate-50 p-4 dark:bg-slate-800/70">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Sponsor Email</p>
            <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-white">{{ $sponsor['email'] }}</p>
        </div>
    </div>

    <div class="mt-6 flex flex-wrap gap-3">
        <a href="mailto:{{ $sponsor['email'] }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950">Contact Sponsor</a>
        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $sponsor['phone']) }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">{{ $sponsor['phone'] }}</a>
        <x-spa-link :href="route('member.genealogy')" :spa="false" class="inline-flex items-center justify-center rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-700 transition hover:border-blue-300 dark:border-blue-500/20 dark:bg-blue-500/10 dark:text-blue-300">{{ $sponsor['upline_label'] }}</x-spa-link>
    </div>
</div>