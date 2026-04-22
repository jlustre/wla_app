@section('title', 'Admin Dashboard')

@extends('layouts.admin')

@section('content')
<section class="min-h-screen bg-slate-950 text-slate-100">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="flex flex-col gap-4 border-b border-white/10 pb-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-400">WLA Admin Control Center</p>
                <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-white sm:text-4xl">Admin Dashboard</h1>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-300 sm:text-base">
                    Monitor member growth, affiliated companies, sponsorship positioning, reassignment activity, resources, and platform health from one centralized operational dashboard.
                </p>
            </div>
        </div>

        @include('admin.dashboard_contents.kpt-cards')
        @include('admin.dashboard_contents.companies-overview')
        @include('admin.dashboard_contents.analytics')
        @include('admin.dashboard_contents.fear-loss-monitoring')
        @include('admin.dashboard_contents.recent-activity')
        @include('admin.dashboard_contents.reassignment-audit')
        @include('admin.dashboard_contents.resources-announcements')
        @include('admin.dashboard_contents.cta-admin-utility')
    </div>
</section>
@endsection