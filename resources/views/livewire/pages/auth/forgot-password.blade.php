<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div class="bg-teal-800 p-2 sm:p-3 m:p-4 max-w-7xl">
    <div class="mx-auto flex max-w-7xl items-center justify-center">
        <div class="bg-white p-2 md:p-4">
            <div class="mx-auto w-full max-w-xl">
                <div class="flex justify-center">
                    <a href="/" wire:navigate>
                        <x-application-logo class="w-110 h-110 fill-current text-gray-500" />
                    </a>
                </div>
                <div class="mb-4">
                    <h2 class="mt-0 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl text-center">
                        Forgot Your Password?
                    </h2>
                    <p class="mt-3 text-sm leading-4 text-slate-600 sm:text-base">
                        No problem. Enter your email address below and we will email you a password reset link so you can choose a new one.
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form class="space-y-4" wire:submit.prevent="sendPasswordResetLink">
                    <!-- Email Address -->
                    <div>
                        <label for="email" class="mb-1 text-sm font-semibold text-slate-800 flex items-center gap-2">
                            Email Address<span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 7.5v9A2.25 2.25 0 0119.5 18.75h-15A2.25 2.25 0 012.25 16.5v-9m19.5 0A2.25 2.25 0 0019.5 5.25h-15A2.25 2.25 0 002.25 7.5m19.5 0l-8.69 5.514a2.25 2.25 0 01-2.12 0L2.25 7.5" />
                                </svg>
                            </span>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                autocomplete="email"
                                placeholder="Enter your email address"
                                class="block w-full rounded-lg border border-teal-600 bg-teal-50 px-3 py-2 pl-12 pr-4 text-md text-slate-900 shadow-sm outline-none transition duration-200 placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                wire:model.defer="email"
                                required autofocus
                            />
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2 pt-2">
                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center rounded-2xl bg-gradient-to-r from-teal-600 via-teal-400 to-teal-800 px-6 py-4 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition duration-200 hover:-translate-y-0.5 hover:from-teal-700 hover:via-teal-700 hover:to-teal-800 focus:outline-none focus:ring-4 focus:ring-blue-200"
                        >
                            Email Password Reset Link
                        </button>
                        <p class="text-center text-sm text-slate-500">
                            Remembered your password?
                            <a href="{{ route('login') }}" class="font-semibold text-blue-700 transition hover:text-blue-800 hover:underline">
                                Log in
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
                <div class="mt-4 text-center text-xs text-slate-400">
                    &copy; {{ date('Y') }} Wealth Legacy Alliance. All rights reserved.
                </div>
            </div>
        </div>
    </div>
</div>
