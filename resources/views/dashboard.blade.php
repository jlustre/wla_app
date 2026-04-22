@section('title', 'Member Dashboard')

@extends('layouts.member')

@section('content')
    <div class="flex items-center justify-between mb-4 mx-2 lg:mx-0">
        <h2 class="truncate text-xl font-bold text-teal-700">Dashboard</h2>
        <p class="mt-1 text-sm text-slate-500">Home / Dashboard</p>
    </div>
    @include('dashboard.welcome-hero')
    @include('dashboard.kpi-cards')
    @include('dashboard.performance-overview')
    @include('dashboard.quick-actions')
    @include('dashboard.middle-grid')
    @include('dashboard.bottom-grid')
@endsection