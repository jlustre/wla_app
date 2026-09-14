@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="w-full">
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-teal-600">Admin</p>
        <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-teal-800">{{ $title }}</h1>
        <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">{{ $description }}</p>
    </div>

    <div class="mb-6 flex flex-wrap gap-3">
        <a href="{{ route('admin.downline') }}" class="rounded-xl bg-teal-600 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-700">Tree Viewer</a>
        <a href="{{ route('admin.downline.placement-rules') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Placement Rules</a>
        <a href="{{ route('admin.downline.spillover') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Spillover Logic</a>
        <a href="{{ route('admin.sponsor-relationships.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Sponsor Relationships</a>
    </div>

    @include('member.main-hierarchy')
</div>
@endsection
