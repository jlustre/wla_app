<nav class="space-y-2 mt-2" x-data="{
    openMenu: null,
    toggle(menu) {
        this.openMenu = this.openMenu === menu ? null : menu;
    },
    closeMenus(e) {
        if (!e.target.closest('.menu-group')) this.openMenu = null;
    }
}" @click.away="closeMenus($event)">
    <!-- Dashboard -->
    <a
        href="{{ route('dashboard') }}"
        class="flex items-center gap-3 rounded-2xl bg-white/10 px-4 py-3 text-sm font-medium text-white shadow-inner ring-1 ring-white/10"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 text-cyan-300"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="1.8"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M3.75 3.75h7.5v7.5h-7.5zm9 0h7.5v4.5h-7.5zm0 6h7.5v10.5h-7.5zm-9 9h7.5v1.5h-7.5z"
            />
        </svg>
        <span>
                Dashboard
        </span>
    </a>

    <!-- My Network -->
    <div class="space-y-2">
        <button
            @click="networkOpen = !networkOpen"
            class="flex w-full items-center justify-between rounded-2xl px-4 py-3 text-sm font-medium text-white/85 hover:bg-teal-600 hover:text-white"
        >
            <span class="flex items-center gap-3">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 text-teal-300"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M7.5 7.5a2.25 2.25 0 1 0 0-4.5 2.25 2.25 0 0 0 0 4.5Zm9 0a2.25 2.25 0 1 0 0-4.5 2.25 2.25 0 0 0 0 4.5ZM12 21a3 3 0 0 0-3-3H6.75a3 3 0 0 0-3 3m12.75 0a3 3 0 0 0-3-3h-3a3 3 0 0 0-3 3m12.75 0a3 3 0 0 0-3-3H15a3 3 0 0 0-3 3m0-9a2.25 2.25 0 1 0 0-4.5 2.25 2.25 0 0 0 0 4.5Z"
                    />
                </svg>
                My Network
            </span>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 transition-transform"
                :class="networkOpen ? 'rotate-180' : ''"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.8"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="m19.5 8.25-7.5 7.5-7.5-7.5"
                />
            </svg>
        </button>
        <div
            x-show="networkOpen"
            x-transition
            class="ml-4 space-y-1 border-l border-white/10 pl-4"
        >
            <a
                href="#"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-teal-600 hover:text-white"
                >Overview</a
            >
            <a
                href="#"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Genealogy Tree</a
            >
            <a
                href="#"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Sponsorship Tree</a
            >
            <a
                href="#"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Direct Referrals</a
            >
            <a
                href="#"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Team Members</a
            >
        </div>
    </div>

    <!-- Prospects -->
    <div class="space-y-2">
        <button
            @click="prospectsOpen = !prospectsOpen"
            class="flex w-full items-center justify-between rounded-2xl px-4 py-3 text-sm font-medium text-white/85 hover:bg-teal-600 hover:text-white"
        >
            <span class="flex items-center gap-3">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 text-teal-300"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M17.25 6.75v10.5m-5.25-7.5v7.5m-5.25-4.5v4.5M4.5 19.5h15"
                    />
                </svg>
                Prospects
            </span>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 transition-transform"
                :class="prospectsOpen ? 'rotate-180' : ''"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.8"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="m19.5 8.25-7.5 7.5-7.5-7.5"
                />
            </svg>
        </button>
        <div
            x-show="prospectsOpen"
            x-transition
            class="ml-4 space-y-1 border-l border-white/10 pl-4"
        >
            <a href="{{ route('prospects.index') }}" class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                            >Prospects Dashboard</a
                        >
            <a href="{{ route('lead-pipeline') }}"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Lead Pipeline</a>
            <a href="#" class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Follow-ups</a>
            <a href="#"
                href="{{ route('kanban-command-center') }}"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Kanban Command Center</a>
        </div>
    </div>

    <!-- Companies -->
    <div class="space-y-2">
        <div class="menu-group">
        <button
            @click="toggle('companies')"
            class="flex w-full items-center justify-between rounded-2xl px-4 py-3 text-sm font-medium text-white/85 hover:bg-teal-600 hover:text-white"
        >
            <span class="flex items-center gap-3">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 text-teal-300"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3.75 21h16.5M4.5 3.75h15v13.5h-15zm3 3h3m-3 3h6"
                    />
                </svg>
                Companies
            </span>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 transition-transform"
                :class="openMenu === 'companies' ? 'rotate-180' : ''"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.8"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="m19.5 8.25-7.5 7.5-7.5-7.5"
                />
            </svg>
        </button>
        <div
            x-show="openMenu === 'companies'"
            x-transition
            class="ml-4 space-y-1 border-l border-white/10 pl-4"
        >
            <x-company-sidebar-menu />
        </div>
    </div>

    <!-- Training -->
    <div class="space-y-2">
        <button
            @click="trainingOpen = !trainingOpen"
            class="flex w-full items-center justify-between rounded-2xl px-4 py-3 text-sm font-medium text-white/85 hover:bg-teal-600 hover:text-white"
        >
            <span class="flex items-center gap-3">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 text-teal-300"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 6.75v10.5m5.25-7.5-10.5 6m10.5 0-10.5-6"
                    />
                </svg>
                Training Center
            </span>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 transition-transform"
                :class="trainingOpen ? 'rotate-180' : ''"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.8"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="m19.5 8.25-7.5 7.5-7.5-7.5"
                />
            </svg>
        </button>
        <div
            x-show="trainingOpen"
            x-transition
            class="ml-4 space-y-1 border-l border-white/10 pl-4"
        >
            <a
                href="#"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Video Library</a
            >
            <a
                href="#"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Documents</a
            >
            <a
                href="#"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Quick Start Guide</a
            >
            <a
                href="#"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Scripts &amp; Templates</a
            >
        </div>
    </div>

    <!-- Calendar -->
    <div class="space-y-2">
        <button
            @click="calendarOpen = !calendarOpen"
            class="flex w-full items-center justify-between rounded-2xl px-4 py-3 text-sm font-medium text-white/85 hover:bg-teal-600 hover:text-white"
        >
            <span class="flex items-center gap-3">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 text-teal-300"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M5.25 5.25h13.5A1.5 1.5 0 0 1 20.25 6.75v11.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V6.75a1.5 1.5 0 0 1 1.5-1.5Z"
                    />
                </svg>
                Calendar &amp; Activities
            </span>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 transition-transform"
                :class="calendarOpen ? 'rotate-180' : ''"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.8"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="m19.5 8.25-7.5 7.5-7.5-7.5"
                />
            </svg>
        </button>
        <div
            x-show="calendarOpen"
            x-transition
            class="ml-4 space-y-1 border-l border-white/10 pl-4"
        >
            <a
                href="#"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Appointments</a
            >
            <a
                href="#"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Tasks</a
            >
            <a
                href="#"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Events</a
            >
            <a
                href="#"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Reminders</a
            >
        </div>
    </div>

    <!-- Earnings -->
    <div class="space-y-2">
        <button
            @click="earningsOpen = !earningsOpen"
            class="flex w-full items-center justify-between rounded-2xl px-4 py-3 text-sm font-medium text-white/85 hover:bg-white/8 hover:text-white"
        >
            <span class="flex items-center gap-3">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 text-teal-300"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 6v12m4.5-9.75A4.5 4.5 0 0 0 12 4.5c-2.485 0-4.5 1.567-4.5 3.5s2.015 3.5 4.5 3.5 4.5 1.567 4.5 3.5-2.015 3.5-4.5 3.5a4.5 4.5 0 0 1-4.5-3.75"
                    />
                </svg>
                Earnings
            </span>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 transition-transform"
                :class="earningsOpen ? 'rotate-180' : ''"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.8"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="m19.5 8.25-7.5 7.5-7.5-7.5"
                />
            </svg>
        </button>
        <div
            x-show="earningsOpen"
            x-transition
            class="ml-4 space-y-1 border-l border-white/10 pl-4"
        >
            <a
                href="#"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Commissions</a
            >
            <a
                href="#"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Overrides</a
            >
            <a
                href="#"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Bonuses</a
            >
            <a
                href="#"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Wallet / Payouts</a
            >
        </div>
    </div>

    <!-- Reports -->
    <div class="space-y-2">
        <button
            @click="reportsOpen = !reportsOpen"
            class="flex w-full items-center justify-between rounded-2xl px-4 py-3 text-sm font-medium text-white/85 hover:bg-white/8 hover:text-white"
        >
            <span class="flex items-center gap-3">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 text-teal-300"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 18.75h18M6.75 15V9m5.25 6V5.25M17.25 15v-3.75"
                    />
                </svg>
                Reports
            </span>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 transition-transform"
                :class="reportsOpen ? 'rotate-180' : ''"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.8"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="m19.5 8.25-7.5 7.5-7.5-7.5"
                />
            </svg>
        </button>
        <div
            x-show="reportsOpen"
            x-transition
            class="ml-4 space-y-1 border-l border-white/10 pl-4"
        >
            <a
                href="#"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Performance Reports</a
            >
            <a
                href="#"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Recruitment Reports</a
            >
            <a
                href="#"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Activity Logs</a
            >
            <a
                href="#"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Earnings Summary</a
            >
        </div>
    </div>

    <!-- Single Links -->
    <a
        href="#"
        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium text-white/85 hover:bg-white/8 hover:text-white"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 text-teal-300"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="1.8"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M8.625 9.75h6.75m-9 4.5h11.25M6 18.75h12A2.25 2.25 0 0 0 20.25 16.5V7.5A2.25 2.25 0 0 0 18 5.25H6A2.25 2.25 0 0 0 3.75 7.5v9A2.25 2.25 0 0 0 6 18.75Z"
            />
        </svg>
        Messages
    </a>

    <a
        href="#"
        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium text-white/85 hover:bg-white/8 hover:text-white"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 text-teal-300"
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
        Notifications
    </a>

    <!-- Settings -->
    <div class="space-y-2">
        <button
            @click="settingsOpen = !settingsOpen"
            class="flex w-full items-center justify-between rounded-2xl px-4 py-3 text-sm font-medium text-white/85 hover:bg-white/8 hover:text-white"
        >
            <span class="flex items-center gap-3">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 text-teal-300"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.592c.55 0 1.02.398 1.11.94l.213 1.28c.063.379.313.7.663.848.265.112.522.238.77.377.328.184.729.183 1.037-.017l1.04-.674a1.125 1.125 0 0 1 1.42.128l1.832 1.832c.39.39.445 1.003.128 1.42l-.674 1.04a1.125 1.125 0 0 0-.017 1.037c.139.248.265.505.377.77.148.35.47.6.848.663l1.28.213c.542.09.94.56.94 1.11v2.592c0 .55-.398 1.02-.94 1.11l-1.28.213a1.125 1.125 0 0 0-.848.663c-.112.265-.238.522-.377.77a1.125 1.125 0 0 0-.017 1.037l-.674-1.04c.317.417.262 1.03-.128 1.42l-1.832 1.832a1.125 1.125 0 0 1-1.42.128l-1.04-.674a1.125 1.125 0 0 0-1.037-.017c-.248.139-.505.265-.77.377.35-.148.6-.47.663-.848l.213-1.28Z"
                    />
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                    />
                </svg>
                Settings
            </span>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 transition-transform"
                :class="settingsOpen ? 'rotate-180' : ''"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.8"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="m19.5 8.25-7.5 7.5-7.5-7.5"
                />
            </svg>
        </button>
        <div
            x-show="settingsOpen"
            x-transition
            class="ml-4 space-y-1 border-l border-white/10 pl-4"
        >
            <a
                href="#"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Profile Settings</a
            >
            <a
                href="#"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Account Settings</a
            >
            <a
                href="#"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Security</a
            >
            <a
                href="#"
                class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-white/8"
                >Preferences</a
            >
        </div>
    </div>

    <a
        href="#"
        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium text-white/85 hover:bg-white/8 hover:text-white"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 text-teal-300"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="1.8"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M18 10.5h.008v.008H18V10.5Zm-6 0h.008v.008H12V10.5Zm-6 0h.008v.008H6V10.5Zm12 4.5h.008v.008H18V15Zm-6 0h.008v.008H12V15Zm-6 0h.008v.008H6V15Zm-.75-10.5h13.5A1.5 1.5 0 0 1 20.25 6v12a1.5 1.5 0 0 1-1.5 1.5H5.25A1.5 1.5 0 0 1 3.75 18V6a1.5 1.5 0 0 1 1.5-1.5Z"
            />
        </svg>
        Support
    </a>
</nav>