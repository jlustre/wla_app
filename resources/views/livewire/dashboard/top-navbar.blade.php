<header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/85 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/80">
    <div class="mx-auto flex h-20 items-center gap-4 px-4 sm:px-6 lg:px-8">
        <div class="flex min-w-0 flex-1 items-center gap-3">
            <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:border-emerald-300 hover:text-emerald-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 lg:hidden" @click="$dispatch('toggle-mobile-sidebar')">
                <span class="sr-only">Open navigation</span>
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" d="M4.5 7.5h15M4.5 12h15M4.5 16.5h15" /></svg>
            </button>

            <button type="button" class="hidden h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:border-emerald-300 hover:text-emerald-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 lg:inline-flex" @click="$dispatch('toggle-desktop-sidebar')">
                <span class="sr-only">Hide or show sidebar</span>
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3.5" y="4.5" width="17" height="15" rx="2.5" /><path stroke-linecap="round" d="M9.5 4.5v15" /></svg>
            </button>

            <x-spa-link :href="route('landing.how')" :spa="false" class="wla-topbar-brand shrink-0 items-center pr-1">
                <x-application-logo class="h-14 w-auto shrink-0 object-contain" />
            </x-spa-link>

            <div class="wla-topbar-title min-w-0" x-data="{ title: @js($title) }" x-on:livewire:navigated.window="title = document.title.split('|')[0].trim()">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-600 dark:text-emerald-300">{{ $eyebrow }}</p>
                <h1 class="truncate text-2xl font-bold text-slate-950 dark:text-white" x-text="title">{{ $title }}</h1>
            </div>
        </div>

        <div class="hidden min-w-0 flex-1 xl:block">
            <label class="relative block">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                    <x-dashboard-icon name="magnifying-glass" class="h-4 w-4" />
                </span>
                <input type="text" placeholder="Search members, invites, webinars, resources..." class="h-12 w-full rounded-2xl border border-slate-200 bg-slate-50 pl-11 pr-4 text-sm text-slate-900 outline-none transition focus:border-emerald-400 focus:bg-white focus:ring-4 focus:ring-emerald-100 dark:border-slate-800 dark:bg-slate-900 dark:text-white dark:focus:ring-emerald-950">
            </label>
        </div>

        <div class="flex items-center gap-3">
            <livewire:dashboard.theme-toggle />

            <button type="button" class="hidden h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:border-emerald-300 hover:text-emerald-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 md:inline-flex">
                <span class="sr-only">Messages</span>
                <x-dashboard-icon name="chat-bubble-left-right" class="h-5 w-5" />
            </button>

            <livewire:dashboard.notification-dropdown />
            <livewire:dashboard.avatar-dropdown />
        </div>
    </div>
</header>
