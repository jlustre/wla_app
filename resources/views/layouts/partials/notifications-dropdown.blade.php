<div
    class="relative"
    x-data="{ open: false }"
>
    <button
        @click="open = !open"
        class="relative inline-flex h-11 w-11 items-center justify-center"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="1.8"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022 23.848 23.848 0 0 0 5.454 1.31m5.715 0a24.255 24.255 0 0 1-5.715 0m5.715 0a3 3 0 1 1-5.715 0"
            />
        </svg>
        <span
            class="absolute right-2 top-2 h-2.5 w-2.5 rounded-full bg-cyan-500 ring-2 ring-white"
        ></span>
    </button>

    <div
        x-show="open"
        @click.away="open = false"
        x-transition
        class="absolute right-0 mt-3 w-96 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl"
    >
        <div
            class="border-b border-slate-100 px-5 py-4"
        >
            <div
                class="flex items-center justify-between"
            >
                <h3
                    class="text-sm font-semibold text-slate-900"
                >
                    Notifications
                </h3>
                <a
                    href="{{ \App\Support\Nav::route('admin.notifications') }}"
                    class="text-xs font-medium text-blue-600 hover:text-blue-700"
                    >Mark all as read</a
                >
            </div>
        </div>
        <div
            class="max-h-96 overflow-y-auto"
        >
            <a
                href="{{ \App\Support\Nav::route('admin.notifications') }}"
                class="flex gap-3 px-5 py-4 hover:bg-slate-50"
            >
                <div
                    class="mt-1 h-2.5 w-2.5 rounded-full bg-cyan-500"
                ></div>
                <div>
                    <p
                        class="text-sm font-medium text-slate-800"
                    >
                        New referral joined
                        your network
                    </p>
                    <p
                        class="mt-1 text-sm text-slate-500"
                    >
                        Maria Santos joined
                        under your primary
                        leg.
                    </p>
                    <p
                        class="mt-2 text-xs text-slate-400"
                    >
                        5 minutes ago
                    </p>
                </div>
            </a>
            <a
                href="{{ \App\Support\Nav::route('admin.notifications') }}"
                class="flex gap-3 px-5 py-4 hover:bg-slate-50"
            >
                <div
                    class="mt-1 h-2.5 w-2.5 rounded-full bg-blue-500"
                ></div>
                <div>
                    <p
                        class="text-sm font-medium text-slate-800"
                    >
                        Prospect follow-up
                        due today
                    </p>
                    <p
                        class="mt-1 text-sm text-slate-500"
                    >
                        Call Jonathan about
                        company overview
                        presentation.
                    </p>
                    <p
                        class="mt-2 text-xs text-slate-400"
                    >
                        22 minutes ago
                    </p>
                </div>
            </a>
            <a
                href="{{ \App\Support\Nav::route('admin.notifications') }}"
                class="flex gap-3 px-5 py-4 hover:bg-slate-50"
            >
                <div
                    class="mt-1 h-2.5 w-2.5 rounded-full bg-emerald-500"
                ></div>
                <div>
                    <p
                        class="text-sm font-medium text-slate-800"
                    >
                        Commission posted
                        successfully
                    </p>
                    <p
                        class="mt-1 text-sm text-slate-500"
                    >
                        $1,480 commission
                        has been added to
                        your wallet.
                    </p>
                    <p
                        class="mt-2 text-xs text-slate-400"
                    >
                        1 hour ago
                    </p>
                </div>
            </a>
            <a
                href="{{ \App\Support\Nav::route('admin.notifications') }}"
                class="flex gap-3 px-5 py-4 hover:bg-slate-50"
            >
                <div
                    class="mt-1 h-2.5 w-2.5 rounded-full bg-violet-500"
                ></div>
                <div>
                    <p
                        class="text-sm font-medium text-slate-800"
                    >
                        New training video
                        added
                    </p>
                    <p
                        class="mt-1 text-sm text-slate-500"
                    >
                        The Team Duplication
                        System training is
                        now available.
                    </p>
                    <p
                        class="mt-2 text-xs text-slate-400"
                    >
                        3 hours ago
                    </p>
                </div>
            </a>
        </div>
        <div
            class="border-t border-slate-100 px-5 py-3"
        >
            <a
                href="{{ \App\Support\Nav::route('admin.notifications') }}"
                class="text-sm font-semibold text-blue-600 hover:text-blue-700"
                >View all notifications</a
            >
        </div>
    </div>
</div>