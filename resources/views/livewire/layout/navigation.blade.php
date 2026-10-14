<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white border-b border-slate-200 shadow-sm fixed w-full z-50 top-0 left-0">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-row items-center py-1.5 w-full">
            <!-- Logo and Title -->
            <div class="flex items-center relative flex-shrink-0 w-auto md:w-1/3">
                <img src="{{ asset('images/branding/wla-logo-horizontal.png') }}" alt="Wealth Legacy Alliance Logo" class="h-14 sm:h-16 w-auto shrink-0 object-contain">
            </div>
            <div class="flex flex-1 justify-end items-center relative">
                <!-- Hamburger Button (mobile only, far right) -->
                <div class="md:hidden flex items-center absolute right-0 top-1/2 -translate-y-1/2">
                    <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-600 hover:text-emerald-600 hover:bg-slate-100 focus:outline-none focus:bg-slate-100 focus:text-emerald-600 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <!-- Navigation Links (hidden on small screens, visible on md and up) -->
                <div id="navigation-links" class="hidden md:flex flex-row space-x-6 text-base font-medium text-slate-700">
                    <a href="{{ route('landing.how') }}#hero" class="hover:text-emerald-600 transition">Home</a>
                    <a href="{{ route('landing.how') }}#about" class="hover:text-emerald-600 transition">About</a>
                    <a href="{{ route('landing.how') }}#features" class="hover:text-emerald-600 transition">Features</a>
                    <a href="{{ route('landing.how') }}#how" class="hover:text-emerald-600 transition">How It Works</a>
                    <a href="{{ route('landing.how') }}#testimonials" class="hover:text-emerald-600 transition">Testimonials</a>
                    <a href="{{ route('landing.how') }}#contact" class="hover:text-emerald-600 transition">Contact</a>
                </div>
                <div class="hidden md:ml-6 md:block">
                    <livewire:dashboard.theme-toggle wire:key="nav-theme-toggle-desktop" />
                </div>

                <!-- Auth Buttons (only show if guest, hidden if authenticated) -->
                @guest
                <div class="hidden md:flex items-center space-x-3 ml-4">
                    <a href="{{ route('login') }}" class="px-4 py-1.5 text-sm font-semibold text-slate-700 border border-slate-300 rounded-full hover:bg-slate-100 transition">Login</a>
                    <a href="{{ route('register') }}" class="px-4 py-1.5 text-sm font-semibold text-white bg-emerald-600 rounded-full hover:bg-emerald-500 shadow-lg shadow-emerald-900/20 transition">Register</a>
                </div>
                @else
                <!-- Avatar Dropdown Desktop -->
                <div x-data="{ userDropdown: false }" class="hidden md:block ml-8 relative">
                    <button @click="userDropdown = !userDropdown" @keydown.escape="userDropdown = false" class="flex items-center space-x-2 focus:outline-none">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-emerald-600 text-white font-bold text-lg uppercase">{{ strtoupper(auth()->user()->username[0] ?? '?') }}</span>
                        <span class="text-slate-900 font-semibold">{{ auth()->user()->username }}</span>
                        <svg class="w-4 h-4 text-slate-600 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="userDropdown" @click.away="userDropdown = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-50" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                        <a href="{{ route('profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Profile</a>
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Dashboard</a>
                        @if(auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Admin Dashboard</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Logoff</button>
                        </form>
                    </div>
                </div>
                @endguest
                <!-- Mobile Nav (visible on small screens, hidden on md and up) -->
                <!-- Hamburger now on left, so this block is removed -->

            <!-- Settings Dropdown -->
            <!-- Guest Dropdown removed -->
        </div>
    </div>

    <!-- Mobile nav and auth buttons (toggle with hamburger) -->
    <div x-show="open" x-on:click.away="open = false" class="md:hidden bg-white border-t border-slate-200 shadow-lg px-4 pb-4 pt-2 fixed left-0 h-full z-50" x-bind:style="open ? 'width: 40vw; max-width: 160px; top: 56px;' : ''">
        <div class="flex flex-col space-y-2 text-slate-700">
            <a href="{{ route('landing.how') }}#hero" class="hover:text-emerald-600 transition">Home</a>
            <a href="{{ route('landing.how') }}#about" class="hover:text-emerald-600 transition">About</a>
            <a href="{{ route('landing.how') }}#features" class="hover:text-emerald-600 transition">Features</a>
            <a href="{{ route('landing.how') }}#how" class="hover:text-emerald-600 transition">How It Works</a>
            <a href="{{ route('landing.how') }}#testimonials" class="hover:text-emerald-600 transition">Testimonials</a>
            <a href="{{ route('landing.how') }}#contact" class="hover:text-emerald-600 transition">Contact</a>

            <div class="mt-2 flex items-center gap-2">
                <livewire:dashboard.theme-toggle wire:key="nav-theme-toggle-mobile" />
                <span class="text-sm text-slate-500">Toggle theme</span>
            </div>

            @guest
            <a href="{{ route('login') }}" class="mt-2 px-4 py-1.5 text-sm font-semibold text-slate-700 border border-slate-300 rounded-full hover:bg-slate-100 transition">Login</a>
            <a href="{{ route('register') }}" class="mt-2 px-4 py-1.5 text-sm font-semibold text-white bg-emerald-600 rounded-full hover:bg-emerald-500 shadow-lg shadow-emerald-900/20 transition">Register</a>
            @else
            <!-- Avatar Dropdown Mobile -->
            <div x-data="{ userDropdown: false }" class="mt-2">
                <button @click="userDropdown = !userDropdown" @keydown.escape="userDropdown = false" class="flex items-center space-x-2 w-full focus:outline-none">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-emerald-600 text-white font-bold text-lg uppercase">{{ strtoupper(auth()->user()->username[0] ?? '?') }}</span>
                    <span class="text-slate-900 font-semibold">{{ auth()->user()->username }}</span>
                    <svg class="w-4 h-4 text-slate-600 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="userDropdown" @click.away="userDropdown = false" class="mt-2 w-full bg-white rounded-md shadow-lg py-2 z-50" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                    <a href="{{ route('profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Profile</a>
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Dashboard</a>
                    @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Admin Dashboard</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Logoff</button>
                    </form>
                </div>
            </div>
            @endguest
        </div>
    </div>
</nav>