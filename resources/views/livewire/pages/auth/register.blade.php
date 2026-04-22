<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $username = '';
    public string $sponsor = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $terms = false;

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'username' => ['required', 'string', 'max:32', 'alpha_dash', 'unique:'.User::class],
            'sponsor' => ['required', 'string', 'max:32', 'alpha_dash'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
        ]);

        // Validate sponsor exists and is active
        $sponsor = User::where('username', $validated['sponsor'])->where('status', 'active')->first();
        if (!$sponsor) {
            $this->addError('sponsor', 'You can only be sponsored by a current active member of the site.');
            return;
        }

        $validated['sponsor_id'] = $sponsor->id;
        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = 'active';

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen bg-teal-800 p-2 sm:p-3 m:p-4 max-w-7xl">
    <div class="mx-auto flex min-h-[calc(100vh-5rem)] max-w-7xl items-center justify-center">
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
                            Create Your Account
                        </h2>
                        
                        <p class="mt-3 text-sm leading-4 text-slate-600 sm:text-base">
                            Register below to begin your journey with Wealth Legacy Alliance. Complete your details carefully, especially your sponsor information, to ensure proper member placement.
                        </p>
                    </div>

                    <form class="space-y-4" wire:submit.prevent="register">
                        <!-- Username -->
                        <div>
                            <label for="username" class="mb-1 text-sm font-semibold text-slate-800 flex items-center gap-2">
                                Username <span class="text-rose-500">*</span>
                                <span class="relative group">
                                    <svg class="inline h-4 w-4 text-teal-600 cursor-pointer" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-4m0-4h.01" />
                                    </svg>
                                    <span class="absolute left-6 top-1/2 z-10 hidden w-64 -translate-y-1/2 rounded bg-slate-800 px-3 py-2 text-xs text-white shadow-lg group-hover:block">
                                        This will be used as your account identifier inside the platform.
                                    </span>
                                </span>
                            </label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.118a7.5 7.5 0 0115 0A17.933 17.933 0 0112 21.75a17.933 17.933 0 01-7.5-1.632z" />
                                    </svg>
                                </span>
                                <input
                                    id="username"
                                    name="username"
                                    type="text"
                                    autocomplete="username"
                                    placeholder="Choose a unique username"
                                    class="block w-full rounded-lg border border-teal-600 bg-teal-50 px-3 py-2 pl-12 pr-4 text-md text-slate-900 shadow-sm outline-none transition duration-200 placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                    wire:model.defer="username"
                                />
                            </div>
                            @error('username')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sponsor -->
                        <div>
                            <label for="sponsor" class="mb-1 text-sm font-semibold text-slate-800 flex items-center gap-2">
                                Sponsor <span class="text-rose-500">*</span>
                                <span class="relative group">
                                    <svg class="inline h-4 w-4 text-slate-500 cursor-pointer" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-4m0-4h.01" />
                                    </svg>
                                    <span class="absolute left-6 top-1/2 z-10 hidden w-64 -translate-y-1/2 rounded bg-slate-800 px-3 py-2 text-xs text-white shadow-lg group-hover:block">
                                        If you came through a referral link, this field may be prefilled automatically. Your sponsor is the member who invited you into WLA. Please make sure this information is correct before submitting your registration.
                                    </span>
                                </span>
                            </label>
                            <div class="relative mt-1">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-blue-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9a3 3 0 10-6 0v1H9a3 3 0 000 6h6a3 3 0 000-6h-1V9z" />
                                    </svg>
                                </span>
                                <input
                                    id="sponsor"
                                    name="sponsor"
                                    type="text"
                                    placeholder="Enter sponsor username or referral code"
                                    class="block w-full rounded-lg border border-teal-600 bg-teal-50 px-3 py-2 pl-12 pr-4 text-md text-slate-900 shadow-sm outline-none transition duration-200 placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                    wire:model.defer="sponsor"
                                />
                            </div>
                            @error('sponsor')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="mb-1 text-sm font-semibold text-slate-800 flex items-center gap-2">
                                Email Address<span class="text-rose-500">*</span>
                                <span class="relative group">
                                    <svg class="inline h-4 w-4 text-slate-800 cursor-pointer" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-4m0-4h.01" />
                                    </svg>
                                    <span class="absolute left-6 top-1/2 z-10 hidden w-64 -translate-y-1/2 rounded bg-slate-800 px-3 py-2 text-xs text-white shadow-lg group-hover:block">
                                        We’ll use this email for account verification and important platform updates.
                                    </span>
                                </span>
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
                                />
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="mb-1 text-sm font-semibold text-slate-800 flex items-center gap-2">
                                Password<span class="text-rose-500">*</span>
                                <span class="relative group">
                                    <svg class="inline h-4 w-4 text-slate-400 cursor-pointer" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-4m0-4h.01" />
                                    </svg>
                                    <span class="absolute left-6 top-1/2 z-10 hidden w-72 -translate-y-1/2 rounded bg-slate-800 px-3 py-2 text-xs text-white shadow-lg group-hover:block">
                                        <span class="font-semibold uppercase tracking-wide">Password guidance</span>
                                        <ul class="mt-2 space-y-1 list-disc list-inside">
                                            <li>Minimum of 8 characters</li>
                                            <li>Include uppercase and lowercase letters</li>
                                            <li>Include at least one number or special character</li>
                                        </ul>
                                    </span>
                                </span>
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
                                    placeholder="Create a strong password"
                                    class="block w-full rounded-lg border border-teal-600 bg-teal-50 px-3 py-2 pl-12 pr-14 text-sm text-slate-900 shadow-sm outline-none transition duration-200 placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                    wire:model.defer="password"
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
                            <!-- Tooltip moved to label -->
                            @error('password')
                                <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div>
                            <label for="password_confirmation" class="mb-1 text-sm font-semibold text-slate-800 flex items-center gap-2">
                                Password Confirmation<span class="text-rose-500">*</span>
                                <span class="relative group">
                                    <svg class="inline h-4 w-4 text-slate-400 cursor-pointer" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-4m0-4h.01" />
                                    </svg>
                                    <span class="absolute left-6 top-1/2 z-10 hidden w-64 -translate-y-1/2 rounded bg-slate-800 px-3 py-2 text-xs text-white shadow-lg group-hover:block">
                                        Please enter the same password again to confirm.
                                    </span>
                                </span>
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
                                    placeholder="Re-enter your password"
                                    class="block w-full rounded-lg border border-teal-600 bg-teal-50 px-3 py-2 pl-12 pr-14 text-sm text-slate-900 shadow-sm outline-none transition duration-200 placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                    wire:model.defer="password_confirmation"
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

                        <!-- Terms -->
                        <div>
                            <label for="terms" class="flex items-start gap-3">
                                <input
                                    id="terms"
                                    name="terms"
                                    type="checkbox"
                                    class="mt-0 h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-2 focus:ring-blue-500"
                                    wire:model.defer="terms"
                                />
                                <span class="text-xs leading-4 text-slate-600">
                                    I agree to the
                                    <a href="#" class="font-semibold text-blue-700 transition hover:text-blue-800 hover:underline">
                                        Terms and Conditions
                                    </a>
                                    and
                                    <a href="#" class="font-semibold text-blue-700 transition hover:text-blue-800 hover:underline">
                                        Privacy Policy
                                    </a>.
                                </span>
                            </label>
                            @error('terms')
                                <p class="mt-2 pl-8 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit -->
                        <div class="space-y-2 pt-2">
                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center rounded-2xl bg-gradient-to-r from-teal-600 via-teal-400 to-teal-800 px-6 py-4 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition duration-200 hover:-translate-y-0.5 hover:from-teal-700 hover:via-teal-700 hover:to-teal-800 focus:outline-none focus:ring-4 focus:ring-blue-200"
                            >
                                Create Account
                            </button>

                            <p class="text-center text-sm text-slate-500">
                                Already have an account?
                                <a href="#" class="font-semibold text-blue-700 transition hover:text-blue-800 hover:underline">
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