<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $user = auth()->user();
        if ($user && $user->hasAnyRole(['admin', 'super-admin'])) {
            $this->redirectIntended(default: route('admin.dashboard', absolute: false), navigate: true);
        } elseif ($user && $user->hasRole('member')) {
            $this->redirectIntended(default: route('member.dashboard', absolute: false), navigate: true);
        } else {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
        }
    }
}; ?>

<div class="bg-teal-800 p-2 sm:p-3 m:p-4 max-w-7xl">
    <div class="mx-auto flex max-w-7xl items-center justify-center">
            <!-- Right Form Panel -->
            <div class="bg-white p-2 md:p-4">
                <div class="mx-auto w-full max-w-xl">
                    <div class="flex justify-center">
                        <a href="/" wire:navigate>
                            <x-application-logo class="w-110 h-110 fill-current text-gray-500" />
                        </a>
                    </div>
                    <div class="mb-4">
                        <h2 class="mt-0 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl text-center">
                            Welcome Back
                        </h2>
                        <p class="mt-3 text-sm leading-4 text-slate-600 sm:text-base">
                            Log in to your Wealth Legacy Alliance account to continue your journey. Enter your credentials below.
                        </p>
                    </div>

                    <form class="space-y-4" wire:submit.prevent="login">
                        <!-- Username or Email -->
                        <div>
                            <label for="login" class="mb-1 text-sm font-semibold text-slate-800 flex items-center gap-2">
                                Username or Email<span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 7.5v9A2.25 2.25 0 0119.5 18.75h-15A2.25 2.25 0 012.25 16.5v-9m19.5 0A2.25 2.25 0 0019.5 5.25h-15A2.25 2.25 0 002.25 7.5m19.5 0l-8.69 5.514a2.25 2.25 0 01-2.12 0L2.25 7.5" />
                                    </svg>
                                </span>
                                <input
                                    id="login"
                                    name="login"
                                    type="text"
                                    autocomplete="username"
                                    placeholder="Enter your username or email address"
                                    class="block w-full rounded-lg border border-teal-600 bg-teal-50 px-3 py-2 pl-12 pr-4 text-md text-slate-900 shadow-sm outline-none transition duration-200 placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                    wire:model.defer="form.login"
                                />
                            </div>
                            @error('form.login')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="mb-1 text-sm font-semibold text-slate-800 flex items-center gap-2">
                                Password<span class="text-rose-500">*</span>
                            </label>
                            <div class="relative" x-data="{ show: false }">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V7.875a4.5 4.5 0 10-9 0V10.5m-.75 0h10.5A2.25 2.25 0 0119.5 12.75v6A2.25 2.25 0 0117.25 21h-10.5A2.25 2.25 0 014.5 18.75v-6A2.25 2.25 0 016.75 10.5z" />
                                    </svg>
                                </span>
                                <input
                                    id="password"
                                    name="password"
                                    :type="show ? 'text' : 'password'"
                                    autocomplete="current-password"
                                    placeholder="Enter your password"
                                    class="block w-full rounded-lg border border-teal-600 bg-teal-50 px-3 py-2 pl-12 pr-14 text-sm text-slate-900 shadow-sm outline-none transition duration-200 placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                    wire:model.defer="form.password"
                                />
                                <button
                                    type="button"
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-sm font-medium text-slate-500 transition hover:text-blue-600"
                                    @click="show = !show"
                                    :aria-label="show ? 'Hide password' : 'Show password'"
                                >
                                    <span x-text="show ? 'Hide' : 'Show'"></span>
                                </button>
                            </div>
                            @error('form.password')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <label for="remember" class="flex items-center gap-2">
                                <input
                                    id="remember"
                                    name="remember"
                                    type="checkbox"
                                    class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-2 focus:ring-blue-500"
                                    wire:model.defer="form.remember"
                                />
                                <span class="text-xs text-slate-600">Remember me</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="text-xs text-blue-700 hover:underline">Forgot password?</a>
                        </div>

                        <!-- Submit -->
                        <div class="space-y-2 pt-2">
                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center rounded-2xl bg-gradient-to-r from-teal-600 via-teal-400 to-teal-800 px-6 py-4 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition duration-200 hover:-translate-y-0.5 hover:from-teal-700 hover:via-teal-700 hover:to-teal-800 focus:outline-none focus:ring-4 focus:ring-blue-200"
                            >
                                Log In
                            </button>

                            <p class="text-center text-sm text-slate-500">
                                Don't have an account?
                                <a href="{{ route('register') }}" class="font-semibold text-blue-700 transition hover:text-blue-800 hover:underline">
                                    Register
                                </a>
                            </p>
                        </div>
                    </form>

                    <!-- Footer Note -->
                    <div class="mt-4 border-t border-slate-200 pt-4">
                        <p class="text-center text-xs leading-2 text-slate-500">
                            By joining Wealth Legacy Alliance, you are entering a professional member-based platform designed to support growth, connection, and long-term opportunity.
                        </p>
                    </div>
                    <p class="mt-2 text-xs text-slate-500 flex items-center justify-start gap-1">
                        <span class="text-rose-500 text-base font-bold">*</span> <span>Required</span>
                    </p> 
                    <div class="mt-4 text-center text-xs text-slate-400">
                        &copy; {{ date('Y') }} Wealth Legacy Alliance. All rights reserved.
                    </div> 
                </div>
            </div>
    </div>
</div>