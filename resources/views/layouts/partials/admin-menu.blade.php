<div
    class="flex-1 overflow-y-auto px-4 py-6 scrollbar-thin scrollbar-thumb-slate-300 scrollbar-track-transparent"
>

    <!-- Core -->
    <div class="mb-8" x-data="{ downlineOpen: false }">
        <p
            class="mb-3 px-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400"
        >
            Overview
        </p>
        <nav class="space-y-1">
          

            <div class="rounded-2xl">
                <button
                    @click="downlineOpen = !downlineOpen"
                    class="flex w-full items-center justify-between rounded-2xl px-4 py-3 text-left text-slate-200 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-300 font-medium hover:text-indigo-700 active:bg-indigo-500"
                >
                    <span class="flex items-center gap-3">
                        <svg
                            class="h-5 w-5 text-slate-400"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M8 7v10m8-10v10M4 4h16v16H4V4z"
                            />
                        </svg>
                        Downline Tree
                    </span>
                    <svg
                        class="h-4 w-4 text-slate-400 transition-transform"
                        :class="downlineOpen ? 'rotate-180' : ''"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M19 9l-7 7-7-7"
                        />
                    </svg>
                </button>
                <div class="ml-11 mt-1 space-y-1" x-show="downlineOpen" x-transition>
                    <a
                        href="#"
                        class="block rounded-xl px-3 py-2 text-sm text-slate-200 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-300 font-medium hover:text-indigo-700 active:bg-indigo-500"
                        >Tree Viewer</a
                    >
                    <a
                        href="#"
                        class="block rounded-xl px-3 py-2 text-sm text-slate-200 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-300 font-medium hover:text-indigo-700 active:bg-indigo-500"
                        >Placement Rules</a
                    >
                    <a
                        href="#"
                        class="block rounded-xl px-3 py-2 text-sm text-slate-200 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-300 font-medium hover:text-indigo-700 active:bg-indigo-500"
                        >Spillover Logic</a
                    >
                </div>
            </div>

        </nav>
    </div>

    <!-- CRM -->
    <div class="mb-8">
        <p
            class="mb-3 px-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400"
        >
            CRM
        </p>
        <nav class="space-y-1">
            <a
                href="#"
                class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-slate-200 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-300 font-medium hover:text-indigo-700 active:bg-indigo-500"
            >
                <svg
                    class="h-5 w-5 text-slate-400 group-hover:text-slate-700"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M18 9a3 3 0 11-6 0 3 3 0 016 0zm-9 8a4 4 0 018 0M5 7h3m-3 5h5m-5 5h4"
                    />
                </svg>
                Prospects
            </a>

            <a
                href="#"
                class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-slate-200 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-300 font-medium hover:text-indigo-700 active:bg-indigo-500"
            >
                <svg
                    class="h-5 w-5 text-slate-400 group-hover:text-slate-700"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 17v-6m4 6V7m4 10v-3M5 20h14"
                    />
                </svg>
                Leads Pipeline
            </a>

            <a
                href="#"
                class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-slate-200 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-300 font-medium hover:text-indigo-700 active:bg-indigo-500"
            >
                <svg
                    class="h-5 w-5 text-slate-400 group-hover:text-slate-700"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M8 7V3m8 4V3m-9 8h10m-13 9h16a1 1 0 001-1V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a1 1 0 001 1z"
                    />
                </svg>
                Follow-ups
            </a>

            <a
                href="#"
                class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-slate-200 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-300 font-medium hover:text-indigo-700 active:bg-indigo-500"
            >
                <svg
                    class="h-5 w-5 text-slate-400 group-hover:text-slate-700"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"
                    />
                </svg>
                Activity Logs
            </a>

            <a
                href="#"
                class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-slate-200 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-300 font-medium hover:text-indigo-700 active:bg-indigo-500"
            >
                <svg
                    class="h-5 w-5 text-slate-400 group-hover:text-slate-700"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0h6z"
                    />
                </svg>
                Notifications
            </a>
        </nav>
    </div>

    <!-- Administration -->
    <div>
        <p
            class="mb-3 px-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400"
        >
            Administration
        </p>
        <nav class="space-y-1">
              <a
                href="#"
                class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-slate-200 transition-all duration-200 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-300 font-medium hover:text-indigo-700 active:bg-indigo-500 "
            >
                <svg
                    class="h-5 w-5 text-slate-400 group-hover:text-slate-700"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M17 20h5V4H2v16h5m10 0v-8H7v8m10 0H7"
                    />
                </svg>
                Members Management
            </a>
            <a
                href="#"
                class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-slate-200 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-300 font-medium hover:text-indigo-700 active:bg-indigo-500"
            >
                <svg
                    class="h-5 w-5 text-slate-400 group-hover:text-slate-700"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 14c2.761 0 5-2.239 5-5S14.761 4 12 4 7 6.239 7 9s2.239 5 5 5zm0 0c-4.418 0-8 1.79-8 4v2h16v-2c0-2.21-3.582-4-8-4z"
                    />
                </svg>
                Role Management
            </a>

                <a
                    href="{{ route('admin.companies.index') }}"
                    class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-slate-200 hover:bg-gradient-to-r hover:from-teal-100 hover:to-cyan-300 font-medium hover:text-teal-800 active:bg-teal-500"
                >
                    <svg
                        class="h-5 w-5 text-cyan-300 group-hover:text-teal-700"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3.75 3.75h7.5v7.5h-7.5zm9 0h7.5v4.5h-7.5zm0 6h7.5v10.5h-7.5zm-9 9h7.5v1.5h-7.5z"
                        />
                    </svg>
                    Company Management
                </a>

            <a
                href="#"
                class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-slate-200 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-300 font-medium hover:text-indigo-700 active:bg-indigo-500"
            >
                <svg
                    class="h-5 w-5 text-slate-400 group-hover:text-slate-700"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M10.325 4.317a1 1 0 011.35-.936l.76.332a1 1 0 00.8 0l.76-.332a1 1 0 011.35.936l.06.826a1 1 0 00.5.79l.7.41a1 1 0 01.37 1.37l-.4.72a1 1 0 000 .8l.4.72a1 1 0 01-.37 1.37l-.7.41a1 1 0 00-.5.79l-.06.826a1 1 0 01-1.35.936l-.76-.332a1 1 0 00-.8 0l-.76.332a1 1 0 01-1.35-.936l-.06-.826a1 1 0 00-.5-.79l-.7-.41a1 1 0 01-.37-1.37l.4-.72a1 1 0 000-.8l-.4-.72a1 1 0 01.37-1.37l.7-.41a1 1 0 00.5-.79l.06-.826z"
                    />
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 15a3 3 0 100-6 3 3 0 000 6z"
                    />
                </svg>
                Settings
            </a>

            <a
                href="#"
                class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-slate-200 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-300 font-medium hover:text-indigo-700 active:bg-indigo-500"
            >
                <svg
                    class="h-5 w-5 text-slate-400 group-hover:text-slate-700"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"
                    />
                </svg>
                Audit Logs
            </a>
                        <a
                href="#"
                class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-slate-200 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-300 font-medium hover:text-indigo-700 active:bg-indigo-500"
            >
                <svg
                    class="h-5 w-5 text-slate-400 group-hover:text-slate-700"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 17v-4m3 4V7m3 10v-6m4 9H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2z"
                    />
                </svg>
                Reports & Analytics
            </a>

            <a
                href="#"
                class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-slate-200 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-300 font-medium hover:text-indigo-700 active:bg-indigo-500"
            >
                <svg
                    class="h-5 w-5 text-slate-400 group-hover:text-slate-700"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9.75 3.75h4.5m-7.5 9a5.25 5.25 0 1110.5 0c0 1.37-.52 2.62-1.38 3.56-.68.75-1.12 1.7-1.12 2.71v.23a1.5 1.5 0 01-1.5 1.5h-1a1.5 1.5 0 01-1.5-1.5v-.23c0-1.01-.44-1.96-1.12-2.71A5.22 5.22 0 016.75 12.75z"
                    />
                </svg>
                AI Insights
            </a>
        </nav>
    </div>
</div>