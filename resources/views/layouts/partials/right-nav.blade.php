<div class="flex items-center gap-3">
    <!-- Search -->
    <div class="hidden md:block">
        <div class="relative">
            <span
                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"
                    />
                </svg>
            </span>
            <input
                type="text"
                placeholder="Search prospects, team, reports..."
                class="h-11 w-72 rounded-2xl border border-slate-200 bg-slate-50 pl-11 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-100"
            />
        </div>
    </div>

    <!-- Quick Add -->
    <a
        href="{{ \App\Support\Nav::route('prospects.create') }}"
        class="hidden rounded-2xl bg-gradient-to-r from-blue-700 to-cyan-500 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-200 transition hover:translate-y-[-1px] hover:shadow-xl sm:inline-flex"
    >
        + Add Prospect
    </a>

    <!-- Notifications -->
    @include('layouts.partials.notifications-dropdown')

    <!-- Profile -->
    @include('layouts.partials.profile-dropdown')
</div>