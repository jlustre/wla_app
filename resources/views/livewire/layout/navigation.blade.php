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

<nav x-data="{ open: false }" class="bg-[#1a2537] border-b border-gray-900 fixed w-full z-50 top-0 left-0">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-row items-center h-16 w-full">
            <!-- Logo and Title -->
            <div class="flex items-center relative flex-shrink-0 w-auto md:w-1/3">
                <span class="text-white font-bold text-lg text-center md:text-left w-full md:w-auto">Wealth Legacy <span class="text-teal-300">Alliance</span></span>
            </div>
            <div class="flex flex-1 justify-end items-center relative">
                <!-- Hamburger Button (mobile only, far right) -->
                <div class="md:hidden flex items-center absolute right-0 top-1/2 -translate-y-1/2">
                    <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-teal-300 hover:text-emerald-400 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-emerald-400 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <!-- Navigation Links (hidden on small screens, visible on md and up) -->
                <div id="navigation-links" class="hidden md:flex flex-row space-x-6 text-sm font-medium text-white">
                    <a href="#hero" class="hover:text-emerald-400 transition">Home</a>
                    <a href="#about" class="hover:text-emerald-400 transition">About</a>
                    <a href="#features" class="hover:text-emerald-400 transition">Features</a>
                    <a href="#how" class="hover:text-emerald-400 transition">How It Works</a>
                    <a href="#testimonials" class="hover:text-emerald-400 transition">Testimonials</a>
                    <a href="#contact" class="hover:text-emerald-400 transition">Contact</a>
                </div>
                <!-- Auth Buttons (only show if guest, hidden if authenticated) -->
                @guest
                <div class="hidden md:flex items-center space-x-4 ml-8">
                    <a href="{{ route('login') }}" class="px-5 py-2 text-sm font-semibold text-white border border-white/30 rounded-full hover:bg-white/10 transition">Login</a>
                    <a href="{{ route('register') }}" class="px-5 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-full hover:bg-emerald-500 shadow-lg shadow-emerald-900/20 transition">Register</a>
                </div>
                @else
                <!-- Avatar Dropdown Desktop -->
                <div x-data="{ userDropdown: false }" class="hidden md:block ml-8 relative">
                    <button @click="userDropdown = !userDropdown" @keydown.escape="userDropdown = false" class="flex items-center space-x-2 focus:outline-none">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-emerald-600 text-white font-bold text-lg uppercase">{{ strtoupper(auth()->user()->username[0] ?? '?') }}</span>
                        <span class="text-white font-semibold">{{ auth()->user()->username }}</span>
                        <svg class="w-4 h-4 text-white ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
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
    <div x-show="open" x-on:click.away="open = false" class="md:hidden bg-[#1a2537] border-t border-gray-800 px-4 pb-4 pt-2 fixed left-0 h-full z-50" x-bind:style="open ? 'width: 40vw; max-width: 160px; top: 56px;' : ''">
        <div class="flex flex-col space-y-2 text-white">
            <a href="#hero" class="hover:text-emerald-400 transition">Home</a>
            <a href="#about" class="hover:text-emerald-400 transition">About</a>
            <a href="#features" class="hover:text-emerald-400 transition">Features</a>
            <a href="#how" class="hover:text-emerald-400 transition">How It Works</a>
            <a href="#testimonials" class="hover:text-emerald-400 transition">Testimonials</a>
            <a href="#contact" class="hover:text-emerald-400 transition">Contact</a>
            @guest
            <a href="{{ route('login') }}" class="mt-2 px-5 py-2 text-sm font-semibold text-white border border-white/30 rounded-full hover:bg-white/10 transition">Login</a>
            <a href="{{ route('register') }}" class="mt-2 px-5 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-full hover:bg-emerald-500 shadow-lg shadow-emerald-900/20 transition">Register</a>
            @else
            <!-- Avatar Dropdown Mobile -->
            <div x-data="{ userDropdown: false }" class="mt-2">
                <button @click="userDropdown = !userDropdown" @keydown.escape="userDropdown = false" class="flex items-center space-x-2 w-full focus:outline-none">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-emerald-600 text-white font-bold text-lg uppercase">{{ strtoupper(auth()->user()->username[0] ?? '?') }}</span>
                    <span class="text-white font-semibold">{{ auth()->user()->username }}</span>
                    <svg class="w-4 h-4 text-white ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
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