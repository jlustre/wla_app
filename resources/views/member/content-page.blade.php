@section('title', $page['title'])

@extends('layouts.member')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[36px] border border-white/60 wla-hero p-8 text-white shadow-[0_30px_100px_-55px_rgba(15,23,42,0.95)]">
            <p class="text-xs font-semibold uppercase tracking-[0.32em] text-emerald-300">{{ $page['eyebrow'] }}</p>
            <h1 class="mt-4 text-3xl font-bold tracking-tight sm:text-4xl">{{ $page['title'] }}</h1>
            <p class="mt-4 max-w-3xl text-sm leading-6 text-slate-200">{{ $page['description'] }}</p>
        </div>

        <livewire:member.content-hub :page="$page['key']" :title="$page['title']" :description="$page['description']" />
    </section>
@endsection