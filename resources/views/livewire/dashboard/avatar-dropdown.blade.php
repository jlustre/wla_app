<div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false" x-on:livewire:navigated.window="open = false">
    <button type="button" class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2 shadow-sm transition hover:border-blue-300 dark:border-slate-800 dark:bg-slate-900" @click="open = ! open">
        <div class="hidden text-right sm:block">
            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $user?->username ?? 'Member' }}</p>
            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $user?->email ?? 'member@wealthlegacyalliance.test' }}</p>
        </div>
        <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-slate-900 text-sm font-bold text-white">
            {{ strtoupper(substr($user?->username ?? 'M', 0, 1)) }}
        </span>
    </button>

    <div x-cloak x-show="open" x-transition.origin.top.right @click.outside="open = false" class="absolute right-0 z-50 mt-3 w-80 overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_32px_90px_-45px_rgba(15,23,42,0.6)] dark:border-slate-800 dark:bg-slate-900">
        <div class="border-b border-slate-200 px-5 py-5 dark:border-slate-800">
            <p class="text-base font-semibold text-slate-900 dark:text-white">{{ $user?->username ?? 'Member' }}</p>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $user?->email ?? 'member@wealthlegacyalliance.test' }}</p>
            <div class="mt-3 flex items-center gap-2">
                <livewire:dashboard.status-badge :label="\Illuminate\Support\Str::headline((string) ($user?->status?->value ?? $user?->status ?? 'active'))" tone="blue" />
                <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-300">{{ $user?->getRoleNames()->first() ?? 'member' }}</span>
            </div>
        </div>

        <div class="px-3 py-3">
            <x-spa-link :href="route('profile')" class="flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800">
                <x-dashboard-icon name="user" class="h-4 w-4" />
                <span>View Profile</span>
            </x-spa-link>
            <x-spa-link :href="route('member.settings.profile')" class="flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800">
                <x-dashboard-icon name="cog" class="h-4 w-4" />
                <span>Account Settings</span>
            </x-spa-link>
            <x-spa-link :href="route('member.settings.privacy')" class="flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800">
                <x-dashboard-icon name="shield" class="h-4 w-4" />
                <span>Privacy Settings</span>
            </x-spa-link>
            <x-spa-link :href="route('member.support')" class="flex items-center gap-3 rounded-2xl px-3 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800">
                <x-dashboard-icon name="life-buoy" class="h-4 w-4" />
                <span>Help / Support</span>
            </x-spa-link>
        </div>

        <div class="border-t border-slate-200 px-3 py-3 dark:border-slate-800">
            <livewire:dashboard.logout-form />
        </div>
    </div>
</div>