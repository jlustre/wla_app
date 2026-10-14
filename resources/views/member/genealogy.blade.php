@section('title', 'Sponsorship Tree')

@extends('layouts.member')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[36px] border border-white/60 wla-hero p-8 text-white shadow-[0_30px_100px_-55px_rgba(15,23,42,0.95)]">
            <p class="text-xs font-semibold uppercase tracking-[0.32em] text-emerald-300">Sponsorship Network</p>
            <h1 class="mt-4 text-3xl font-bold tracking-tight sm:text-4xl">Interactive genealogy explorer</h1>
            <p class="mt-4 max-w-3xl text-sm leading-6 text-slate-200">The tree now focuses on one branch at a time so you can navigate dense sponsorship structures without overlapping cards, unreadable connectors, or horizontal clutter.</p>
        </div>

        @include('member.main-hierarchy')
    </section>
@endsection
