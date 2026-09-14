@section('title', 'Admin Dashboard')

@extends('layouts.admin')

@section('content')
<section class="space-y-6">
    <div class="rounded-[36px] border border-white/60 bg-[linear-gradient(135deg,rgba(11,23,48,0.98),rgba(17,31,61,0.94)_65%,rgba(47,111,237,0.86))] p-8 text-white shadow-[0_30px_100px_-55px_rgba(15,23,42,0.95)]">
        <p class="text-xs font-semibold uppercase tracking-[0.32em] text-blue-200">WLA Admin Control Center</p>
        <h1 class="mt-4 text-3xl font-bold tracking-tight sm:text-4xl">Admin Dashboard</h1>
        <p class="mt-4 max-w-3xl text-sm leading-6 text-slate-200">
            Monitor member growth, affiliated companies, sponsorship positioning, reassignment activity, resources, and platform health from one centralized operational dashboard.
        </p>
    </div>

    <div class="overflow-hidden rounded-[36px] border border-slate-200/80 bg-slate-950 text-slate-100 shadow-[0_30px_100px_-55px_rgba(15,23,42,0.65)] dark:border-slate-800">
        <div class="px-4 py-6 sm:px-6 lg:px-8">
            @include('admin.dashboard_contents.kpt-cards')
            @include('admin.dashboard_contents.companies-overview')
            @include('admin.dashboard_contents.analytics')
            @include('admin.dashboard_contents.fear-loss-monitoring')
            @include('admin.dashboard_contents.recent-activity')
            @include('admin.dashboard_contents.reassignment-audit')
            @include('admin.dashboard_contents.resources-announcements')
            @include('admin.dashboard_contents.cta-admin-utility')
        </div>
    </div>
</section>
@endsection
