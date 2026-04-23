<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
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
                        Reset Your Password
                    </h2>
                    <p class="mt-3 text-sm leading-4 text-slate-600 sm:text-base">
                        Enter your email address and new password below to reset your account password.
                    </p>
                </div>

                <form class="space-y-4" wire:submit.prevent="resetPassword">
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
                                autocomplete="username"
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

                    <!-- Password -->
                    <div>
                        <label for="password" class="mb-1 text-sm font-semibold text-slate-800 flex items-center gap-2">
                            New Password<span class="text-rose-500">*</span>
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
                                autocomplete="new-password"
                                placeholder="Enter your new password"
                                class="block w-full rounded-lg border border-teal-600 bg-teal-50 px-3 py-2 pl-12 pr-14 text-sm text-slate-900 shadow-sm outline-none transition duration-200 placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                wire:model.defer="password"
                                required
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
                        @error('password')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="mb-1 text-sm font-semibold text-slate-800 flex items-center gap-2">
                            Confirm New Password<span class="text-rose-500">*</span>
                        </label>
                        <div class="relative" x-data="{ show: false }">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l2.25 2.25L15 9.75m4.5 2.25a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z" />
                                </svg>
                            </span>
                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                :type="show ? 'text' : 'password'"
                                autocomplete="new-password"
                                placeholder="Re-enter your new password"
                                class="block w-full rounded-lg border border-teal-600 bg-teal-50 px-3 py-2 pl-12 pr-14 text-sm text-slate-900 shadow-sm outline-none transition duration-200 placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                wire:model.defer="password_confirmation"
                                required
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
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2 pt-2">
                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center rounded-2xl bg-gradient-to-r from-teal-600 via-teal-400 to-teal-800 px-6 py-4 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition duration-200 hover:-translate-y-0.5 hover:from-teal-700 hover:via-teal-700 hover:to-teal-800 focus:outline-none focus:ring-4 focus:ring-blue-200"
                        >
                            Reset Password
                        </button>
                        <p class="text-center text-sm text-slate-500">
                            Back to
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
