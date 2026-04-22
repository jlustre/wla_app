@extends('layouts.member')

@section('title', 'All Prospects')

@section('content')
<section class="min-h-screen bg-slate-50">
  <div class="space-y-8 p-4 sm:p-6 lg:p-8">
    
    <!-- Scoped Context Header -->
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
      <div class="border-b border-slate-200 bg-gradient-to-r from-slate-900 via-sky-900 to-slate-900 px-6 py-5 text-white">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
          <div>
            <div class="flex flex-wrap items-center gap-2">
              @if(auth()->user() && auth()->user()->hasRole('admin'))
                <span class="inline-flex items-center rounded-full border border-amber-300/30 bg-amber-400/10 px-3 py-1 text-xs font-semibold text-amber-100">
                  Viewing as Administrator
                </span>
              @else
                <span class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-sky-100">
                  User-Scoped Workspace
                </span>
                <span class="inline-flex items-center rounded-full border border-emerald-300/30 bg-emerald-400/10 px-3 py-1 text-xs font-semibold text-emerald-100">
                  All data filtered by user_id
                </span>
              @endif
            </div>
            
            <div class="flex flex-row items-start w-full">
              <div class="flex-1">
                <h1 class="mt-3 text-2xl font-bold tracking-tight sm:text-3xl">
                  Prospects Dashboard
                </h1>
                <p class="mt-2 max-w-3xl text-sm text-slate-200 sm:text-base">
                  This page is showing the exact prospect workspace of the selected  All dashboards, pipeline cards,
                  notes, tasks, communications, AI suggestions, and reports below are scoped to a single owner user.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
        <!-- Admin Member Switcher -->
        @include('prospects.admin-switcher')

        <!-- Quick Add -->
        @include('prospects.quick-add')

        <!-- KPI Cards -->
        @include('prospects.prospects-kpi')
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 gap-6 2xl:grid-cols-12">
      
        <!-- Left Column -->
      <div class="space-y-6 2xl:col-span-8">
        <!-- Daily Action Panel -->
        @include('prospects.daily-action')

        <!-- Prospect Table -->
        @include('prospects.prospects-table')

        <!-- Prospect Profile Preview -->
        @include('prospects.prospects-profile-preview')
      </div>

      <!-- Right Column -->
      @include('prospects.right-column')
    </div>
  </div>
</section>
@endsection
