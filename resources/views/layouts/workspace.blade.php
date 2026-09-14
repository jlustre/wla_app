@php
    $workspace = $workspace ?? 'member';
    $isAdminWorkspace = $workspace === 'admin';
    $defaultTitle = $isAdminWorkspace ? 'Admin Dashboard' : 'Member Dashboard';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="memberDashboardShell()" x-init="init()" :class="theme" x-on:toggle-sidebar.window="sidebarOpen = ! sidebarOpen" x-on:close-sidebar.window="sidebarOpen = false" x-on:toggle-theme.window="toggleTheme()" x-on:set-theme.window="setTheme($event.detail.theme)" x-on:livewire:navigated.window="sidebarOpen = false">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', $title ?? $defaultTitle) | {{ config('app.name', 'WLA') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak] { display: none !important; }
            :root {
                --dashboard-navy: #0b1730;
                --dashboard-panel: #111f3d;
                --dashboard-blue: #2f6fed;
                --dashboard-gold: #d8a94e;
                --dashboard-ink: #10213d;
                --dashboard-soft: #f5f7fb;
            }

            html.dark body {
                color-scheme: dark;
            }

            body {
                font-family: 'Instrument Sans', sans-serif;
            }
        </style>
    </head>
    <body class="min-h-screen bg-slate-100 text-slate-900 antialiased transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
        <div class="relative min-h-screen overflow-x-hidden bg-[radial-gradient(circle_at_top,_rgba(47,111,237,0.12),_transparent_28%),linear-gradient(180deg,_rgba(11,23,48,0.04),_rgba(11,23,48,0))] dark:bg-[radial-gradient(circle_at_top,_rgba(216,169,78,0.12),_transparent_24%),linear-gradient(180deg,_rgba(8,15,33,1),_rgba(8,15,33,0.96))]" x-on:copy-to-clipboard.window="navigator.clipboard?.writeText($event.detail.value)">
            <div class="pointer-events-none absolute inset-x-0 top-0 h-72 bg-gradient-to-br from-blue-600/10 via-transparent to-amber-400/10"></div>

            <div class="relative flex min-h-screen">
                <div x-cloak x-show="sidebarOpen" class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-sm lg:hidden" x-transition.opacity @click="sidebarOpen = false"></div>

                @if ($isAdminWorkspace)
                    @persist('admin-sidebar-mobile')
                    <aside x-cloak class="fixed inset-y-0 left-0 z-50 w-80 max-w-[85vw] border-r border-white/10 bg-[linear-gradient(180deg,#091327_0%,#101e3f_45%,#12254a_100%)] text-white shadow-2xl shadow-slate-950/20 transition duration-300 lg:hidden" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
                        <livewire:dashboard.sidebar :mobile="true" workspace="admin" wire:key="admin-sidebar-mobile" />
                    </aside>
                    @endpersist

                    @persist('admin-sidebar-desktop')
                    <aside class="hidden w-80 border-r border-white/10 bg-[linear-gradient(180deg,#091327_0%,#101e3f_45%,#12254a_100%)] text-white lg:fixed lg:inset-y-0 lg:flex lg:flex-col">
                        <livewire:dashboard.sidebar workspace="admin" wire:key="admin-sidebar-desktop" />
                    </aside>
                    @endpersist
                @else
                    @persist('member-sidebar-mobile')
                    <aside x-cloak class="fixed inset-y-0 left-0 z-50 w-80 max-w-[85vw] border-r border-white/10 bg-[linear-gradient(180deg,#091327_0%,#101e3f_45%,#12254a_100%)] text-white shadow-2xl shadow-slate-950/20 transition duration-300 lg:hidden" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
                        <livewire:dashboard.sidebar :mobile="true" workspace="member" wire:key="member-sidebar-mobile" />
                    </aside>
                    @endpersist

                    @persist('member-sidebar-desktop')
                    <aside class="hidden w-80 border-r border-white/10 bg-[linear-gradient(180deg,#091327_0%,#101e3f_45%,#12254a_100%)] text-white lg:fixed lg:inset-y-0 lg:flex lg:flex-col">
                        <livewire:dashboard.sidebar workspace="member" wire:key="member-sidebar-desktop" />
                    </aside>
                    @endpersist
                @endif

                <div class="flex min-h-screen min-w-0 flex-1 flex-col lg:pl-80">
                    @if ($isAdminWorkspace)
                        @persist('admin-top-navbar')
                            <livewire:dashboard.top-navbar
                                :title="trim($__env->yieldContent('title', $title ?? \Illuminate\Support\Str::of(request()->route()?->getName() ?? $defaultTitle)->replace('.', ' ')->replace('-', ' ')->title()))"
                                eyebrow="Admin dashboard"
                                wire:key="admin-top-navbar"
                            />
                        @endpersist
                    @else
                        @persist('member-top-navbar')
                            <livewire:dashboard.top-navbar
                                :title="trim($__env->yieldContent('title', $title ?? \Illuminate\Support\Str::of(request()->route()?->getName() ?? $defaultTitle)->replace('.', ' ')->replace('-', ' ')->title()))"
                                eyebrow="Member dashboard"
                                wire:key="member-top-navbar"
                            />
                        @endpersist
                    @endif

                    <main class="flex-1 px-4 py-4 sm:px-6 lg:px-8 lg:py-6">
                        @isset($slot)
                            {{ $slot }}
                        @endisset
                        @yield('content')
                    </main>
                </div>
            </div>
        </div>

        <script data-navigate-once>
            function memberDashboardShell() {
                return {
                    sidebarOpen: false,
                    theme: 'light',
                    init() {
                        const savedTheme = localStorage.getItem('wla-theme');
                        const profileTheme = @js(auth()->user()?->profile?->theme_preference);
                        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                        this.theme = savedTheme || profileTheme || (prefersDark ? 'dark' : 'light');
                        document.documentElement.classList.toggle('dark', this.theme === 'dark');
                        localStorage.setItem('wla-theme', this.theme);
                    },
                    toggleTheme() {
                        this.setTheme(this.theme === 'dark' ? 'light' : 'dark');
                    },
                    setTheme(theme) {
                        this.theme = theme === 'dark' ? 'dark' : 'light';
                        document.documentElement.classList.toggle('dark', this.theme === 'dark');
                        localStorage.setItem('wla-theme', this.theme);
                    },
                };
            }

            function memberSidebar() {
                return {
                    path: window.location.pathname,
                    open: {},
                    sync() {
                        this.path = window.location.pathname;
                        this.open = {};
                    },
                    isActive(href, childPaths = []) {
                        if (! href) {
                            return false;
                        }

                        if (this.path === href) {
                            return true;
                        }

                        return childPaths.some((child) => child && (this.path === child || this.path.startsWith(`${child}/`)));
                    },
                    groupOpen(key, href, childPaths = []) {
                        if (Object.prototype.hasOwnProperty.call(this.open, key)) {
                            return this.open[key];
                        }

                        return this.isActive(href, childPaths);
                    },
                    toggleGroup(key, href, childPaths = []) {
                        this.open[key] = ! this.groupOpen(key, href, childPaths);
                    },
                };
            }
        </script>

        @livewireScripts
    </body>
</html>
