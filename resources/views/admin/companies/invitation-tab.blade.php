@php
    $shareSubject = 'Join me on '.$company->name;
    $shareText = 'Join me on '.$company->name.' through my invitation link: ';
@endphp

<div class="grid gap-6 lg:grid-cols-[1.3fr_0.7fr]">
    <div class="space-y-6">
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 sm:p-8" x-data="{ copied: false }">
        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-300">WLA Invitation Link</p>
        <h2 class="mt-2 text-2xl font-extrabold text-white">Your Fixed WLA Link</h2>
        <p class="mt-2 text-sm leading-6 text-slate-400">
            This is your personal WLA invitation link &mdash; the same fixed link, carrying your tracked reference
            code, that works everywhere across the alliance. Share it with prospects interested in
            {{ $company->name }}.
        </p>

        <div class="mt-6 rounded-2xl border border-white/10 bg-slate-900/60 p-4">
            <div class="flex items-center justify-between gap-3">
                <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Your invitation link</label>
                <button
                    type="button"
                    class="inline-flex h-9 shrink-0 items-center justify-center rounded-xl bg-sky-500 px-4 text-xs font-semibold text-white transition hover:bg-sky-400"
                    @click="$dispatch('copy-to-clipboard', { value: {{ \Illuminate\Support\Js::from($inviteLink) }} }); copied = true; setTimeout(() => copied = false, 1800)"
                >
                    <span x-show="! copied">Copy Link</span>
                    <span x-cloak x-show="copied">Copied</span>
                </button>
            </div>
            <p class="mt-3 break-all rounded-xl bg-slate-950/60 px-4 py-3 text-sm text-slate-200">{{ $inviteLink }}</p>

            @if($inviteCode?->expires_at)
                <p class="mt-3 text-xs text-slate-500">This link refreshes every 2 days &mdash; expires {{ $inviteCode->expires_at->diffForHumans() }}.</p>
            @endif
        </div>

        <div class="mt-6">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Share via</p>
            <div class="mt-3 flex flex-wrap gap-3">
                <a href="mailto:?subject={{ urlencode($shareSubject) }}&body={{ urlencode($shareText.$inviteLink) }}" class="inline-flex items-center justify-center rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
                    Email
                </a>
                <a href="https://wa.me/?text={{ urlencode($shareText.$inviteLink) }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
                    WhatsApp
                </a>
                <a href="https://t.me/share/url?url={{ urlencode($inviteLink) }}&text={{ urlencode($shareText) }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
                    Telegram
                </a>
            </div>
        </div>
    </div>

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 sm:p-8" x-data="{ copiedLink: false, copiedSponsor: false }">
        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-300">{{ $company->name }} Registration</p>
        <h2 class="mt-2 text-2xl font-extrabold text-white">Join {{ $company->name }} Directly</h2>

        @if(blank($company->signup_link))
            <p class="mt-2 text-sm leading-6 text-slate-400">
                {{ $company->name }} hasn't added its registration link yet. Check back soon, or use your WLA
                invitation link above in the meantime.
            </p>
        @else
            @if($company->usesSponsorEntry())
                <p class="mt-2 text-sm leading-6 text-slate-400">
                    Unlike your fixed WLA link above, {{ $company->name }} handles registration on its own website
                    &mdash; new members type their sponsor's {{ strtolower($company->sponsorFieldLabel()) }}
                    in directly on that page. Share both pieces of information below.
                </p>

                <div class="mt-6 rounded-2xl border border-amber-400/20 bg-amber-500/10 p-4">
                    <div class="flex items-center justify-between gap-3">
                        <label class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-300">Your Sponsor {{ $company->sponsorFieldLabel() }}</label>
                        <button
                            type="button"
                            class="inline-flex h-9 shrink-0 items-center justify-center rounded-xl bg-amber-500 px-4 text-xs font-semibold text-slate-900 transition hover:bg-amber-400"
                            @click="$dispatch('copy-to-clipboard', { value: {{ \Illuminate\Support\Js::from($company->sponsorValueFor(auth()->user())) }} }); copiedSponsor = true; setTimeout(() => copiedSponsor = false, 1800)"
                        >
                            <span x-show="! copiedSponsor">Copy</span>
                            <span x-cloak x-show="copiedSponsor">Copied</span>
                        </button>
                    </div>
                    <p class="mt-3 text-lg font-bold tracking-wide text-white">{{ $company->sponsorValueFor(auth()->user()) }}</p>
                    <p class="mt-2 text-xs text-slate-300">
                        Tell your prospect to enter this exactly as their sponsor's
                        {{ strtolower($company->sponsorFieldLabel()) }} when they register at the link below.
                    </p>
                </div>
            @else
                <p class="mt-2 text-sm leading-6 text-slate-400">
                    {{ $company->name }} uses a single public registration link &mdash; it doesn't track individual
                    sponsors through WLA. Anyone can register with it, so follow up personally with your prospect
                    after they sign up.
                </p>
            @endif

            <div class="mt-6 rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                <div class="flex items-center justify-between gap-3">
                    <label class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Registration link</label>
                    <button
                        type="button"
                        class="inline-flex h-9 shrink-0 items-center justify-center rounded-xl bg-sky-500 px-4 text-xs font-semibold text-white transition hover:bg-sky-400"
                        @click="$dispatch('copy-to-clipboard', { value: {{ \Illuminate\Support\Js::from($company->signup_link) }} }); copiedLink = true; setTimeout(() => copiedLink = false, 1800)"
                    >
                        <span x-show="! copiedLink">Copy Link</span>
                        <span x-cloak x-show="copiedLink">Copied</span>
                    </button>
                </div>
                <p class="mt-3 break-all rounded-xl bg-slate-950/60 px-4 py-3 text-sm text-slate-200">{{ $company->signup_link }}</p>
                <a href="{{ $company->signup_link }}" target="_blank" rel="noopener" class="mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-sky-300 hover:text-sky-200">
                    Open registration page &rarr;
                </a>
            </div>
        @endif
    </div>
    </div>

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-sky-300">How It Works</p>
        <ul class="mt-4 space-y-4 text-sm leading-6 text-slate-300">
            <li class="flex gap-3">
                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-sky-500/20 text-xs font-bold text-sky-300">1</span>
                <span>Share your link with a prospect interested in {{ $company->name }}.</span>
            </li>
            <li class="flex gap-3">
                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-sky-500/20 text-xs font-bold text-sky-300">2</span>
                <span>They register using the link and are placed under you as sponsor for this company.</span>
            </li>
            <li class="flex gap-3">
                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-sky-500/20 text-xs font-bold text-sky-300">3</span>
                <span>Track them in your sponsored members list once they've joined.</span>
            </li>
        </ul>

        @unless($isJoined)
            <div class="mt-6 rounded-2xl border border-amber-400/20 bg-amber-500/10 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-300">Reminder</p>
                <p class="mt-2 text-sm leading-6 text-slate-200">
                    You're not yet active in {{ $company->name }}. Join the company to strengthen your position before
                    sharing this link with your downline.
                </p>
            </div>
        @endunless

        <x-spa-link :href="\App\Support\Nav::route('member.sponsored-members')" class="mt-6 inline-flex w-full items-center justify-center rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-semibold text-slate-200 transition hover:bg-white/10">
            View Sponsored Members
        </x-spa-link>
    </div>
</div>
