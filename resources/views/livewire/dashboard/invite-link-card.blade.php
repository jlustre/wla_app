<div class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-blue-600 dark:text-blue-300">Invite Center</p>
            <h2 class="mt-3 text-2xl font-bold text-slate-950 dark:text-white">Your personal sponsor link</h2>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Share a compliant onboarding path with sponsor attribution built in.</p>
        </div>
        <div class="flex h-20 w-20 items-center justify-center rounded-3xl border border-dashed border-amber-300 bg-amber-50 text-center text-[11px] font-semibold uppercase tracking-[0.25em] text-amber-700 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-300">
            QR
            <br>
            Code
        </div>
    </div>

    <div class="mt-6 rounded-3xl bg-slate-50 p-4 dark:bg-slate-800/70" x-data="{ copied: false }">
        <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Invite Link</label>
        <div class="mt-3 flex flex-col gap-3 lg:flex-row">
            <input type="text" readonly value="{{ $invite['link'] }}" class="h-12 flex-1 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
            <button type="button" class="inline-flex h-12 items-center justify-center rounded-2xl bg-blue-600 px-5 text-sm font-semibold text-white transition hover:bg-blue-700" @click="navigator.clipboard.writeText('{{ $invite['link'] }}'); copied = true; setTimeout(() => copied = false, 1800)">
                <span x-show="! copied">Copy Link</span>
                <span x-show="copied" x-cloak>Copied</span>
            </button>
        </div>
    </div>

    <div class="mt-6 grid gap-4 sm:grid-cols-3">
        <div class="rounded-3xl bg-slate-50 p-4 dark:bg-slate-800/70">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Accepted</p>
            <p class="mt-2 text-2xl font-bold text-slate-950 dark:text-white">{{ $invite['accepted'] }}</p>
        </div>
        <div class="rounded-3xl bg-slate-50 p-4 dark:bg-slate-800/70">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Pending</p>
            <p class="mt-2 text-2xl font-bold text-slate-950 dark:text-white">{{ $invite['pending'] }}</p>
        </div>
        <div class="rounded-3xl bg-slate-50 p-4 dark:bg-slate-800/70">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Conversion Rate</p>
            <p class="mt-2 text-2xl font-bold text-slate-950 dark:text-white">{{ $invite['conversion_rate'] }}</p>
        </div>
    </div>

    <div class="mt-6 flex flex-wrap gap-3">
        @foreach ($invite['share_links'] as $shareLink)
            <a href="{{ $shareLink['href'] }}" target="_blank" rel="noreferrer" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">{{ $shareLink['label'] }}</a>
        @endforeach
    </div>

    <div class="mt-6">
        <div class="flex items-center justify-between gap-3">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Recently joined members</h3>
            <x-spa-link :href="route('member.sponsored-members')" class="text-sm font-semibold text-blue-600 dark:text-blue-300">View all</x-spa-link>
        </div>
        <div class="mt-3 space-y-3">
            @forelse ($recentMembers as $member)
                <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3 dark:bg-slate-800/70">
                    <div>
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $member['name'] }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $member['email'] }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">{{ $member['status'] }}</p>
                        <p class="text-xs text-slate-400">{{ $member['joined'] }}</p>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-200 px-6 py-10 text-center dark:border-slate-700">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">No new joins yet</p>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Your first sponsored members will appear here.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>