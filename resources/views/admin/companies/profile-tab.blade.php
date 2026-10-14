<div class="grid gap-6 lg:grid-cols-[1.3fr_0.7fr]">
    <div class="space-y-6">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 sm:p-8">
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-300">Company Profile</p>
            <h2 class="mt-2 text-2xl font-extrabold text-white">About {{ $company->name }}</h2>

            @if($company->full_description)
                <p class="mt-4 text-sm leading-7 text-slate-300 sm:text-base">{{ $company->full_description }}</p>
            @elseif($company->short_description)
                <p class="mt-4 text-sm leading-7 text-slate-300 sm:text-base">{{ $company->short_description }}</p>
            @else
                <p class="mt-4 text-sm leading-7 text-slate-400">No company description has been added yet.</p>
            @endif

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Category</p>
                    <p class="mt-2 text-sm font-semibold text-white">{{ $company->category ?? 'N/A' }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">CEO</p>
                    <p class="mt-2 text-sm font-semibold text-white">{{ $company->ceo_name ?? 'N/A' }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Location</p>
                    <p class="mt-2 text-sm font-semibold text-white">{{ $company->location ?? 'N/A' }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Phone</p>
                    <p class="mt-2 text-sm font-semibold text-white">{{ $company->phone ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div>
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-extrabold text-white sm:text-2xl">Quick Actions</h2>
                    <p class="mt-1 text-sm text-slate-400">Everything you need, one click away.</p>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                <a href="{{ $company->website_link ?? '#' }}" target="{{ $company->website_link ? '_blank' : '_self' }}" rel="noopener" class="group rounded-2xl border border-white/10 bg-white/5 p-5 transition hover:border-sky-400/30 hover:bg-sky-500/10">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-500/10 text-sky-300">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 3h7v7"></path><path stroke-linecap="round" stroke-linejoin="round" d="M10 14 21 3"></path><path stroke-linecap="round" stroke-linejoin="round" d="M21 14v4a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6a3 3 0 0 1 3-3h4"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">Visit Official Website</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-400">Open the company's official website for public information and updates.</p>
                        </div>
                    </div>
                </a>

                <button type="button" @click="tab = 'products'" class="group w-full rounded-2xl border border-white/10 bg-white/5 p-5 text-left transition hover:border-sky-400/30 hover:bg-sky-500/10">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-500/10 text-sky-300">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"></rect><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10"></path><path stroke-linecap="round" stroke-linejoin="round" d="M7 12h10"></path><path stroke-linecap="round" stroke-linejoin="round" d="M7 16h6"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">View Products</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-400">See product categories, featured offerings, and important product information.</p>
                        </div>
                    </div>
                </button>

                <button type="button" @click="tab = 'compensation'" class="group w-full rounded-2xl border border-white/10 bg-white/5 p-5 text-left transition hover:border-sky-400/30 hover:bg-sky-500/10">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-500/10 text-sky-300">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18"></path><path stroke-linecap="round" stroke-linejoin="round" d="m7 14 3-3 3 2 4-5"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">View Compensation Plan</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-400">Review how this company pays commissions, overrides, bonuses, and other earnings.</p>
                        </div>
                    </div>
                </button>

                <button type="button" @click="showVideoModal = true" class="group w-full rounded-2xl border border-white/10 bg-white/5 p-5 text-left transition hover:border-sky-400/30 hover:bg-sky-500/10">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-500/10 text-sky-300">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><polygon points="9 7 17 12 9 17 9 7"></polygon><rect x="3" y="5" width="18" height="14" rx="2"></rect></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">Watch Overview Video</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-400">Get a fast overview of the company, products, and why people choose this opportunity.</p>
                        </div>
                    </div>
                </button>

                <a href="{{ $company->signup_link ?? '#' }}" target="{{ $company->signup_link ? '_blank' : '_self' }}" rel="noopener" class="group rounded-2xl border border-white/10 bg-white/5 p-5 transition hover:border-sky-400/30 hover:bg-sky-500/10">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-500/10 text-sky-300">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path stroke-linecap="round" stroke-linejoin="round" d="M19 8v6"></path><path stroke-linecap="round" stroke-linejoin="round" d="M22 11h-6"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">Join / Register</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-400">Start the company registration process and secure your position in this opportunity.</p>
                        </div>
                    </div>
                </a>

                <x-spa-link :href="\App\Support\Nav::route('member.sponsor')" class="group rounded-2xl border border-white/10 bg-white/5 p-5 transition hover:border-sky-400/30 hover:bg-sky-500/10">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-500/10 text-sky-300">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">Contact Sponsor</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-400">Reach out to your sponsor for guidance, questions, and next-step assistance.</p>
                        </div>
                    </div>
                </x-spa-link>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-sky-300">Rate &amp; Review</p>

            <div class="mt-3 flex items-center gap-3">
                <span class="text-3xl font-extrabold text-white">{{ number_format($company->average_rating, 1) }}</span>
                <div>
                    <x-star-rating :rating="$company->average_rating" size="text-base" />
                    <p class="mt-1 text-xs text-slate-400">{{ $company->total_reviews }} {{ Str::plural('review', $company->total_reviews) }}</p>
                </div>
            </div>

            <p class="mt-4 text-sm leading-6 text-slate-300">
                {{ $myReview ? 'You already reviewed '.$company->name.'. You can update it anytime.' : 'Share your experience with '.$company->name.' to help other members decide.' }}
            </p>

            <button type="button" @click="tab = 'reviews'" class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-amber-500/10 px-4 py-2.5 text-sm font-semibold text-amber-300 transition hover:bg-amber-500/20">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.5l2.95 6.28 6.93.8-5.1 4.76 1.38 6.84L12 17.77l-6.16 3.41 1.38-6.84-5.1-4.76 6.93-.8L12 2.5z"></path></svg>
                {{ $myReview ? 'Update Your Review' : 'Write a Review' }}
            </button>
        </div>

        <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-sky-300">Your Position Status</p>
            @if($isJoined)
                <h3 class="mt-3 text-xl font-bold text-white">You're Active Here</h3>
                <p class="mt-3 text-sm leading-7 text-slate-300">
                    You are currently an active member of {{ $company->name }}. Share your invitation link so prospects join under your sponsorship.
                </p>
            @else
                <h3 class="mt-3 text-xl font-bold text-white">Get Positioned Early</h3>
                <p class="mt-3 text-sm leading-7 text-slate-300">
                    You are currently not active in this company. If people in your WLA downline join this company
                    before you do, their sponsorship in this company may move to the next qualified active upline.
                </p>
            @endif

            <div class="mt-5 rounded-2xl border border-amber-400/20 bg-amber-500/10 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-300">Important Notice</p>
                <p class="mt-2 text-sm leading-6 text-slate-200">
                    WLA tracks network structure and positioning. This company handles its own registration,
                    products, rules, and compensation plan.
                </p>
            </div>

            <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2">
                <div class="rounded-2xl border border-white/10 bg-slate-900/70 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Joined Status</p>
                    <p class="mt-2 text-sm font-semibold text-white">{{ $isJoined ? 'Active' : 'Inactive' }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-slate-900/70 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Your Invitation</p>
                    <button type="button" @click="tab = 'invitation'" class="mt-2 text-sm font-semibold text-sky-300 hover:text-sky-200">Get your link &rarr;</button>
                </div>
            </div>
        </div>
    </div>
</div>
