@php
    $workspace = $workspace ?? 'member';
    $isAdminWorkspace = $workspace === 'admin';
    $defaultTitle = $isAdminWorkspace ? 'Admin Dashboard' : 'Member Dashboard';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @include('layouts.partials.theme-script')

        <link rel="icon" type="image/png" href="{{ asset('images/branding/wla-favicon.png') }}">

        <title>@yield('title', $title ?? $defaultTitle) | {{ config('app.name', 'WLA') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('head')

        <style>
            [x-cloak] { display: none !important; }

            html.dark body {
                color-scheme: dark;
            }

            body {
                font-family: Figtree, Inter, ui-sans-serif, system-ui, sans-serif;
            }
        </style>
    </head>
    <body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
        <div class="wla-canvas relative min-h-screen overflow-x-hidden" x-on:copy-to-clipboard.window="navigator.clipboard?.writeText($event.detail.value)">
            <div class="pointer-events-none absolute inset-x-0 top-0 h-72 bg-gradient-to-br from-emerald-500/15 via-transparent to-yellow-400/10"></div>

            <div class="relative flex min-h-screen">
                <div class="wla-sidebar-backdrop fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-sm lg:hidden" @click="$dispatch('close-mobile-sidebar')"></div>

                @if ($isAdminWorkspace)
                    @persist('admin-sidebar-mobile')
                    <aside class="wla-sidebar-mobile fixed inset-y-0 left-0 z-50 w-80 max-w-[85vw] border-r border-white/10 wla-sidebar text-white shadow-2xl shadow-slate-950/20 lg:hidden">
                        <livewire:dashboard.sidebar :mobile="true" workspace="admin" wire:key="admin-sidebar-mobile" />
                    </aside>
                    @endpersist

                    @persist('admin-sidebar-desktop')
                    <aside class="wla-sidebar-desktop hidden w-80 border-r border-white/10 wla-sidebar text-white lg:fixed lg:inset-y-0 lg:flex lg:flex-col">
                        <livewire:dashboard.sidebar workspace="admin" wire:key="admin-sidebar-desktop" />
                    </aside>
                    @endpersist
                @else
                    @persist('member-sidebar-mobile')
                    <aside class="wla-sidebar-mobile fixed inset-y-0 left-0 z-50 w-80 max-w-[85vw] border-r border-white/10 wla-sidebar text-white shadow-2xl shadow-slate-950/20 lg:hidden">
                        <livewire:dashboard.sidebar :mobile="true" workspace="member" wire:key="member-sidebar-mobile" />
                    </aside>
                    @endpersist

                    @persist('member-sidebar-desktop')
                    <aside class="wla-sidebar-desktop hidden w-80 border-r border-white/10 wla-sidebar text-white lg:fixed lg:inset-y-0 lg:flex lg:flex-col">
                        <livewire:dashboard.sidebar workspace="member" wire:key="member-sidebar-desktop" />
                    </aside>
                    @endpersist
                @endif

                <div class="wla-content-shell flex min-h-screen min-w-0 flex-1 flex-col transition-[padding] duration-300 lg:pl-80">
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
