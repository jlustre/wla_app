@section('title', $setting['title'])

@extends('layouts.member')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[36px] border border-white/60 bg-[linear-gradient(135deg,rgba(11,23,48,0.98),rgba(17,31,61,0.94)_65%,rgba(47,111,237,0.86))] p-8 text-white shadow-[0_30px_100px_-55px_rgba(15,23,42,0.95)]">
            <p class="text-xs font-semibold uppercase tracking-[0.32em] text-blue-200">Settings</p>
            <h1 class="mt-4 text-3xl font-bold tracking-tight sm:text-4xl">{{ $setting['title'] }}</h1>
            <p class="mt-4 max-w-3xl text-sm leading-6 text-slate-200">{{ $setting['description'] }}</p>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1fr_0.8fr]">
            <livewire:member.settings-panel :setting="$setting['key']" />

            <div class="rounded-[28px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
                <h2 class="text-xl font-bold text-slate-950 dark:text-white">Related links</h2>
                <div class="mt-4 space-y-3">
                    <x-spa-link :href="route('member.settings.profile')" class="block rounded-2xl bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 dark:bg-slate-800/70 dark:text-slate-200">Profile settings</x-spa-link>
                    <x-spa-link :href="route('member.settings.security')" class="block rounded-2xl bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 dark:bg-slate-800/70 dark:text-slate-200">Account security</x-spa-link>
                    <x-spa-link :href="route('member.settings.notifications')" class="block rounded-2xl bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 dark:bg-slate-800/70 dark:text-slate-200">Notification preferences</x-spa-link>
                    <x-spa-link :href="route('member.settings.privacy')" class="block rounded-2xl bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 dark:bg-slate-800/70 dark:text-slate-200">Privacy settings</x-spa-link>
                    <x-spa-link :href="route('member.settings.theme')" class="block rounded-2xl bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 dark:bg-slate-800/70 dark:text-slate-200">Theme preference</x-spa-link>
                </div>
            </div>
        </div>
    </section>
@endsection