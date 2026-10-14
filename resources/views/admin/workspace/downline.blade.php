@extends('layouts.admin')

@section('title', $title)

@section('content')
<section class="space-y-6">
    <div class="rounded-[36px] border border-white/60 wla-hero p-8 text-white shadow-[0_30px_100px_-55px_rgba(15,23,42,0.95)]">
        <p class="text-xs font-semibold uppercase tracking-[0.32em] text-emerald-300">Downline</p>
        <h1 class="mt-4 text-3xl font-bold tracking-tight sm:text-4xl">{{ $title }}</h1>
        <p class="mt-4 max-w-3xl text-sm leading-6 text-slate-200">{{ $description }}</p>

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('admin.downline.placement-rules') }}" class="inline-flex items-center justify-center rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-semibold text-slate-100 hover:bg-white/10">Placement Rules</a>
            <a href="{{ route('admin.downline.spillover') }}" class="inline-flex items-center justify-center rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-semibold text-slate-100 hover:bg-white/10">Spillover Logic</a>
            <a href="{{ route('admin.sponsor-relationships.index') }}" class="inline-flex items-center justify-center rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-semibold text-slate-100 hover:bg-white/10">Sponsor Relationships</a>
        </div>
    </div>

    <div class="overflow-hidden rounded-[36px] border border-slate-200/80 bg-slate-950 text-slate-100 shadow-[0_30px_100px_-55px_rgba(15,23,42,0.65)] dark:border-slate-800">
        <div class="px-4 py-6 sm:px-6 lg:px-8">
            <livewire:admin.downline-manager />
        </div>
    </div>
</section>
@endsection
