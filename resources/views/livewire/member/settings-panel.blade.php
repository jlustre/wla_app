<div class="space-y-6">
    @if (session('settings-status'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300">
            {{ session('settings-status') }}
        </div>
    @endif

    @if ($isOverview)
        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-[28px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
                <h2 class="text-xl font-bold text-slate-950 dark:text-white">Account overview</h2>
                <div class="mt-5 space-y-4 text-sm text-slate-600 dark:text-slate-300">
                    <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3 dark:bg-slate-800/70">
                        <span>Username</span>
                        <span class="font-semibold text-slate-900 dark:text-white">{{ $user->username }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3 dark:bg-slate-800/70">
                        <span>Email</span>
                        <span class="font-semibold text-slate-900 dark:text-white">{{ $user->email }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3 dark:bg-slate-800/70">
                        <span>Theme</span>
                        <span class="font-semibold capitalize text-slate-900 dark:text-white">{{ $profile?->theme_preference ?? 'light' }}</span>
                    </div>
                </div>
            </div>

            <div class="rounded-[28px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
                <h2 class="text-xl font-bold text-slate-950 dark:text-white">Recommended actions</h2>
                <div class="mt-5 space-y-3">
                    <x-spa-link :href="route('member.settings.profile')" class="block rounded-2xl bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:bg-slate-800/70 dark:text-slate-200 dark:hover:bg-slate-800">Complete profile information</x-spa-link>
                    <x-spa-link :href="route('member.settings.security')" class="block rounded-2xl bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:bg-slate-800/70 dark:text-slate-200 dark:hover:bg-slate-800">Review account security</x-spa-link>
                    <x-spa-link :href="route('member.settings.notifications')" class="block rounded-2xl bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:bg-slate-800/70 dark:text-slate-200 dark:hover:bg-slate-800">Tune notification delivery</x-spa-link>
                </div>
            </div>
        </div>
    @endif

    @if ($setting === 'profile')
        <form wire:submit="saveProfile" class="rounded-[28px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">Username</label>
                    <input wire:model.defer="username" type="text" class="mt-2 h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    @error('username') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">Email</label>
                    <input wire:model.defer="email" type="email" class="mt-2 h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    @error('email') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">Phone Number</label>
                    <input wire:model.defer="phone_number" type="text" class="mt-2 h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    @error('phone_number') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">City</label>
                    <input wire:model.defer="city" type="text" class="mt-2 h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    @error('city') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div class="lg:col-span-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">Profile Bio</label>
                    <textarea wire:model.defer="bio" rows="5" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white"></textarea>
                    @error('bio') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="inline-flex items-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800 dark:bg-white dark:text-slate-950">Save profile</button>
            </div>
        </form>
    @endif

    @if (in_array($setting, ['security', 'password'], true))
        <div class="grid gap-6 xl:grid-cols-[0.85fr_1.15fr]">
            <div class="rounded-[28px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
                <h2 class="text-xl font-bold text-slate-950 dark:text-white">Security status</h2>
                <div class="mt-5 space-y-4 text-sm text-slate-600 dark:text-slate-300">
                    <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3 dark:bg-slate-800/70">
                        <span>Email verification</span>
                        <span class="font-semibold text-slate-900 dark:text-white">{{ $user->hasVerifiedEmail() ? 'Verified' : 'Pending' }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3 dark:bg-slate-800/70">
                        <span>Active sessions</span>
                        <span class="font-semibold text-slate-900 dark:text-white">{{ $activeSessions }}</span>
                    </div>
                    <div class="rounded-2xl bg-amber-50 px-4 py-3 text-amber-800 dark:bg-amber-500/10 dark:text-amber-300">
                        Use a long password and keep security alerts enabled for sponsor-account activity.
                    </div>
                </div>
            </div>

            <form wire:submit="updatePassword" class="rounded-[28px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
                <h2 class="text-xl font-bold text-slate-950 dark:text-white">Change password</h2>
                <div class="mt-5 space-y-5">
                    <div>
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">Current password</label>
                        <input wire:model.defer="current_password" type="password" class="mt-2 h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                        @error('current_password') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">New password</label>
                        <input wire:model.defer="password" type="password" class="mt-2 h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                        @error('password') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-200">Confirm new password</label>
                        <input wire:model.defer="password_confirmation" type="password" class="mt-2 h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-900 outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="inline-flex items-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800 dark:bg-white dark:text-slate-950">Update password</button>
                </div>
            </form>
        </div>
    @endif

    @if ($setting === 'notifications')
        <form wire:submit="saveNotifications" class="rounded-[28px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
            <div class="space-y-4">
                @foreach ([
                    ['model' => 'notify_email', 'title' => 'Email updates', 'copy' => 'Receive sponsor and training updates by email.'],
                    ['model' => 'notify_in_app', 'title' => 'In-app notifications', 'copy' => 'Keep your dashboard bell populated with recent events.'],
                    ['model' => 'notify_webinar_reminders', 'title' => 'Webinar reminders', 'copy' => 'Send reminders before live education sessions.'],
                    ['model' => 'notify_security_alerts', 'title' => 'Security alerts', 'copy' => 'Always notify me about sign-in and password events.'],
                    ['model' => 'notify_sponsor_messages', 'title' => 'Sponsor messages', 'copy' => 'Surface direct sponsor follow-up communication.'],
                ] as $option)
                    <label class="flex items-start justify-between gap-4 rounded-2xl bg-slate-50 px-4 py-4 dark:bg-slate-800/70">
                        <div>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $option['title'] }}</p>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $option['copy'] }}</p>
                        </div>
                        <input wire:model="{{ $option['model'] }}" type="checkbox" class="mt-1 h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    </label>
                @endforeach
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="inline-flex items-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800 dark:bg-white dark:text-slate-950">Save notifications</button>
            </div>
        </form>
    @endif

    @if ($setting === 'privacy')
        <form wire:submit="savePrivacy" class="rounded-[28px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
            <div class="space-y-4">
                @foreach ([
                    ['model' => 'privacy_show_phone_to_downline', 'title' => 'Show phone to downline', 'copy' => 'Allow direct sponsored members to see your support number.'],
                    ['model' => 'privacy_show_email_to_sponsor', 'title' => 'Show email to sponsor', 'copy' => 'Keep your direct sponsor able to contact you by email.'],
                    ['model' => 'privacy_show_city_on_profile', 'title' => 'Show city on profile', 'copy' => 'Display your city in member-facing profile contexts.'],
                ] as $option)
                    <label class="flex items-start justify-between gap-4 rounded-2xl bg-slate-50 px-4 py-4 dark:bg-slate-800/70">
                        <div>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $option['title'] }}</p>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $option['copy'] }}</p>
                        </div>
                        <input wire:model="{{ $option['model'] }}" type="checkbox" class="mt-1 h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    </label>
                @endforeach
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="inline-flex items-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800 dark:bg-white dark:text-slate-950">Save privacy settings</button>
            </div>
        </form>
    @endif

    @if ($setting === 'theme')
        <form wire:submit="saveTheme" class="rounded-[28px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
            <div class="grid gap-4 lg:grid-cols-2">
                @foreach (['light' => 'Light Theme', 'dark' => 'Dark Theme'] as $value => $label)
                    <label class="rounded-3xl border px-5 py-5 transition {{ $theme_preference === $value ? 'border-blue-500 bg-blue-50 dark:border-blue-400 dark:bg-blue-500/10' : 'border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-800/70' }}">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $label }}</p>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $value === 'light' ? 'Bright workspace with clear contrast.' : 'Lower-glare workspace for long sessions.' }}</p>
                            </div>
                            <input wire:model="theme_preference" type="radio" value="{{ $value }}" class="h-5 w-5 border-slate-300 text-blue-600 focus:ring-blue-500">
                        </div>
                    </label>
                @endforeach
            </div>
            @error('theme_preference') <p class="mt-3 text-sm text-rose-600">{{ $message }}</p> @enderror

            <div class="mt-6 flex justify-end">
                <button type="submit" class="inline-flex items-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800 dark:bg-white dark:text-slate-950">Save theme</button>
            </div>
        </form>
    @endif
</div>