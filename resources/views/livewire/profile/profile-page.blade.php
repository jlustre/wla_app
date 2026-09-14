<div class="w-full space-y-6">
    <div class="grid w-full gap-6 xl:grid-cols-12">
        <div class="overflow-hidden rounded-[36px] border border-white/60 bg-[linear-gradient(135deg,rgba(11,23,48,0.98),rgba(17,31,61,0.94)_58%,rgba(47,111,237,0.88))] p-6 text-white shadow-[0_30px_100px_-55px_rgba(15,23,42,0.95)] sm:p-8 xl:col-span-7">
            <div class="flex flex-col gap-8 lg:flex-row lg:items-start lg:justify-between">
                <div class="flex min-w-0 flex-1 flex-col gap-6 sm:flex-row sm:items-start">
                    <img src="{{ $profile['avatar'] }}" alt="{{ $profile['name'] }}" class="h-28 w-28 shrink-0 rounded-[28px] border-4 border-white/20 object-cover shadow-2xl">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-[0.32em] text-blue-200">Member profile</p>
                        <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">{{ $profile['name'] }}</h2>
                        <p class="mt-2 text-sm text-slate-200">{{ $profile['email'] }}</p>
                        <p class="mt-4 max-w-3xl text-sm leading-6 text-slate-200">{{ $profile['bio'] }}</p>
                        <div class="mt-5 flex flex-wrap items-center gap-3">
                            <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-200 dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-500/20">{{ $profile['status'] }}</span>
                            <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-blue-100">{{ $profile['role'] }}</span>
                            <span class="text-xs text-slate-300">Joined {{ $profile['join_date'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex shrink-0 flex-col gap-3 sm:min-w-[13rem]">
                    <x-spa-link :href="\App\Support\Nav::route('member.settings.profile')" class="inline-flex items-center justify-center rounded-2xl bg-white px-4 py-3 text-sm font-semibold text-slate-950 transition hover:bg-slate-100">
                        Edit Profile
                    </x-spa-link>
                    <x-spa-link :href="\App\Support\Nav::route('member.genealogy')" :spa="false" class="inline-flex items-center justify-center rounded-2xl border border-white/20 bg-white/10 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/15">
                        View Tree
                    </x-spa-link>
                </div>
            </div>
        </div>

        <div class="rounded-[36px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85 xl:col-span-5">
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-blue-600 dark:text-blue-300">Referral desk</p>
            <h3 class="mt-3 text-2xl font-bold text-slate-950 dark:text-white">Share your invite link</h3>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Keep sponsor attribution attached to every new member you bring in.</p>

            <div class="mt-5 rounded-3xl bg-slate-50 p-4 dark:bg-slate-800/70" x-data="{ copied: false }">
                <label class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Invite link</label>
                <div class="mt-3 flex flex-col gap-3 sm:flex-row">
                    <input type="text" readonly value="{{ $referral['link'] }}" class="h-12 min-w-0 flex-1 rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 outline-none dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                    <button type="button" class="inline-flex h-12 items-center justify-center rounded-2xl bg-blue-600 px-5 text-sm font-semibold text-white transition hover:bg-blue-700" @click="navigator.clipboard.writeText({{ \Illuminate\Support\Js::from($referral['link']) }}); copied = true; setTimeout(() => copied = false, 1800)">
                        <span x-show="! copied">Copy Link</span>
                        <span x-cloak x-show="copied">Copied</span>
                    </button>
                </div>
            </div>

            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                <div class="rounded-3xl bg-slate-50 p-4 dark:bg-slate-800/70">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Sponsor</p>
                    <p class="mt-2 font-semibold text-slate-950 dark:text-white">{{ $referral['sponsor_name'] }}</p>
                </div>
                <div class="rounded-3xl bg-slate-50 p-4 dark:bg-slate-800/70">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Referral code</p>
                    <p class="mt-2 font-semibold text-slate-950 dark:text-white">{{ $referral['referral_code'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid w-full gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($stats as $stat)
            @php
                $accentMap = [
                    'blue' => 'from-blue-600/20 to-cyan-500/10 text-blue-700 dark:text-blue-300',
                    'gold' => 'from-amber-400/20 to-orange-400/10 text-amber-700 dark:text-amber-300',
                    'emerald' => 'from-emerald-500/20 to-teal-400/10 text-emerald-700 dark:text-emerald-300',
                    'slate' => 'from-slate-400/20 to-slate-300/10 text-slate-700 dark:text-slate-300',
                ];
            @endphp
            <div class="rounded-[28px] border border-slate-200/80 bg-white/90 p-5 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $stat['title'] }}</p>
                        <p class="mt-3 text-3xl font-bold tracking-tight text-slate-950 dark:text-white">{{ $stat['value'] }}</p>
                        @if (($stat['change'] ?? '') !== '')
                            <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">{{ $stat['change'] }}</p>
                        @endif
                    </div>
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br {{ $accentMap[$stat['accent'] ?? 'blue'] ?? $accentMap['blue'] }}">
                        <x-dashboard-icon :name="$stat['icon']" class="h-5 w-5" />
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid w-full gap-6 xl:grid-cols-12" x-data="{ tab: 'profile-info' }">
        <div class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85 xl:col-span-7">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-blue-600 dark:text-blue-300">Account details</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-950 dark:text-white">Profile workspace</h2>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button type="button" class="rounded-2xl px-4 py-2 text-sm font-semibold transition" :class="tab === 'profile-info' ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300'" @click="tab = 'profile-info'">Profile Info</button>
                    <button type="button" class="rounded-2xl px-4 py-2 text-sm font-semibold transition" :class="tab === 'business-info' ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300'" @click="tab = 'business-info'">Business Info</button>
                    <button type="button" class="rounded-2xl px-4 py-2 text-sm font-semibold transition" :class="tab === 'password-change' ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300'" @click="tab = 'password-change'">Password Change</button>
                </div>
            </div>

            <div class="mt-6" x-show="tab === 'profile-info'">
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-3xl bg-slate-50 p-4 dark:bg-slate-800/70">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Username</p>
                        <p class="mt-2 font-semibold text-slate-950 dark:text-white">{{ $profile['name'] }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-4 dark:bg-slate-800/70">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Email</p>
                        <p class="mt-2 break-all font-semibold text-slate-950 dark:text-white">{{ $profile['email'] }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-4 dark:bg-slate-800/70">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Phone</p>
                        <p class="mt-2 font-semibold text-slate-950 dark:text-white">{{ $profile['phone'] }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-4 dark:bg-slate-800/70">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">City</p>
                        <p class="mt-2 font-semibold text-slate-950 dark:text-white">{{ $profile['city'] }}</p>
                    </div>
                </div>
                <p class="mt-5 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $profile['bio'] }}</p>
            </div>

            <div class="mt-6" x-cloak x-show="tab === 'business-info'">
                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="rounded-3xl bg-slate-50 p-4 dark:bg-slate-800/70">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Sponsor</p>
                        <p class="mt-2 font-semibold text-slate-950 dark:text-white">{{ $profile['sponsor_name'] }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-4 dark:bg-slate-800/70">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Profile completion</p>
                        <p class="mt-2 font-semibold text-slate-950 dark:text-white">{{ $profile['completion'] }}%</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-4 dark:bg-slate-800/70">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Companies joined</p>
                        <p class="mt-2 font-semibold text-slate-950 dark:text-white">{{ collect($mlmCompanies)->where('status', 'Joined')->count() }} of {{ count($mlmCompanies) }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-6" x-cloak x-show="tab === 'password-change'">
                <div class="rounded-3xl bg-slate-50 p-5 dark:bg-slate-800/70">
                    <p class="text-sm font-semibold text-slate-950 dark:text-white">Password and security live in account settings.</p>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Update your password, review login protection, and keep this workspace aligned with your security preferences.</p>
                    <x-spa-link :href="\App\Support\Nav::route('member.settings.password')" class="mt-4 inline-flex items-center justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950">
                        Open password settings
                    </x-spa-link>
                </div>
            </div>
        </div>

        <div class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85 xl:col-span-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-blue-600 dark:text-blue-300">Team summary</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-950 dark:text-white">Recent joins</h2>
                </div>
                <x-spa-link :href="\App\Support\Nav::route('member.sponsored-members')" class="text-sm font-semibold text-blue-600 dark:text-blue-300">View all</x-spa-link>
            </div>

            <div class="mt-5 grid grid-cols-2 gap-3">
                <div class="rounded-3xl bg-slate-50 p-4 dark:bg-slate-800/70">
                    <p class="text-2xl font-bold text-slate-950 dark:text-white">24</p>
                    <p class="mt-1 text-xs text-slate-500">Direct referrals</p>
                </div>
                <div class="rounded-3xl bg-slate-50 p-4 dark:bg-slate-800/70">
                    <p class="text-2xl font-bold text-slate-950 dark:text-white">642</p>
                    <p class="mt-1 text-xs text-slate-500">Total team size</p>
                </div>
            </div>

            <div class="mt-5 space-y-3">
                @foreach ($recentMembers as $member)
                    <div class="flex items-center justify-between gap-3 rounded-2xl bg-slate-50 px-4 py-3 dark:bg-slate-800/70">
                        <div class="flex min-w-0 items-center gap-3">
                            <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}" class="h-10 w-10 rounded-full object-cover">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-slate-950 dark:text-white">{{ $member['name'] }}</p>
                                <p class="truncate text-xs text-slate-500">{{ $member['email'] }}</p>
                            </div>
                        </div>
                        <span class="shrink-0 text-xs text-slate-400">{{ $member['date'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-blue-600 dark:text-blue-300">Company participation</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-950 dark:text-white">Where this profile is qualified</h2>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ collect($mlmCompanies)->where('status', 'Joined')->count() }} joined · {{ collect($mlmCompanies)->where('status', 'Not Joined')->count() }} still open</p>
        </div>

        <div class="mt-6 grid w-full gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($mlmCompanies as $company)
                <div class="flex h-full flex-col rounded-[28px] border border-slate-200 bg-slate-50/80 p-5 dark:border-slate-800 dark:bg-slate-800/60">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-950 text-sm font-bold text-white dark:bg-white dark:text-slate-950">{{ $company['logo_letter'] }}</span>
                            <div>
                                <p class="font-semibold text-slate-950 dark:text-white">{{ $company['name'] }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $company['tagline'] }}</p>
                            </div>
                        </div>
                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $this->companyBadgeClass($company['badge_color']) }}">{{ $company['status'] }}</span>
                    </div>
                    <p class="mt-4 flex-1 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $company['description'] }}</p>
                    @if ($company['joined_at'] || $company['footer_note'])
                        <p class="mt-3 text-xs font-semibold {{ $company['footer_note'] ? 'text-amber-600 dark:text-amber-300' : 'text-slate-400' }}">{{ $company['footer_note'] ?? $company['joined_at'] }}</p>
                    @endif
                    <button type="button" wire:click="companyAction({{ \Illuminate\Support\Js::from($company['name']) }})" class="mt-4 inline-flex items-center justify-center rounded-2xl px-4 py-2.5 text-sm font-semibold transition {{ $this->companyActionClass($company['action_style']) }}">
                        {{ $company['action_label'] }}
                    </button>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid w-full gap-6 xl:grid-cols-12">
        <div class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85 xl:col-span-7">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-blue-600 dark:text-blue-300">Prospect tracker</p>
                <h2 class="mt-2 text-2xl font-bold text-slate-950 dark:text-white">Pipeline this week</h2>
            </div>
            <div class="mt-5 overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead>
                        <tr class="text-xs uppercase tracking-[0.18em] text-slate-400">
                            <th class="pb-3 font-semibold">Name</th>
                            <th class="pb-3 font-semibold">Status</th>
                            <th class="pb-3 font-semibold">Last contact</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach ($prospects as $prospect)
                            <tr>
                                <td class="py-3 font-semibold text-slate-950 dark:text-white">{{ $prospect['name'] }}</td>
                                <td class="py-3">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $this->getStatusPillClass($prospect['status']) }}">{{ $prospect['status'] }}</span>
                                </td>
                                <td class="py-3 text-slate-500 dark:text-slate-400">{{ $prospect['last_contact'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-[32px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85 xl:col-span-5">
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-blue-600 dark:text-blue-300">Activity</p>
            <h2 class="mt-2 text-2xl font-bold text-slate-950 dark:text-white">Latest movement</h2>
            <ul class="mt-5 space-y-3">
                @foreach ($activities as $activity)
                    <li class="rounded-3xl bg-slate-50 px-4 py-4 dark:bg-slate-800/70">
                        <div class="flex items-start gap-3">
                            <span class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full {{ $this->activityToneClass($activity['type']) }}"></span>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-3">
                                    <p class="text-sm font-semibold text-slate-950 dark:text-white">{{ $activity['title'] }}</p>
                                    <span class="shrink-0 text-xs text-slate-400">{{ $activity['time'] }}</span>
                                </div>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $activity['message'] }}</p>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="grid w-full gap-3 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($quickActions as $action)
            <x-spa-link :href="\App\Support\Nav::route($action['route'] ?? null)" :spa="($action['route'] ?? '') !== 'member.genealogy'" class="rounded-[28px] border border-slate-200/80 bg-white/90 p-5 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] transition hover:border-blue-300 dark:border-slate-800 dark:bg-slate-900/85">
                <p class="text-sm font-semibold text-slate-950 dark:text-white">{{ $action['label'] }}</p>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $action['value'] }}</p>
            </x-spa-link>
        @endforeach
    </div>
</div>
