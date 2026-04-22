@extends('layouts.member')

@section('title', 'Lead Pipeline')

@section('content')
    <div class="flex items-center justify-between mb-4 mx-2 lg:mx-0">
        <h2 class="truncate text-xl font-bold text-teal-700">Leads Pipeline: {{ auth()->user()->name }}</h2>
        <p class="mt-1 text-sm text-slate-500">Home / Lead Pipeline</p>
    </div>

    <div class="min-h-screen bg-slate-50 flex flex-col items-center justify-start pt-0" x-data="{ showModal: false, selected: null, timelines: [] }">
        <div class="w-full max-w-4xl lg:max-w-6xl xl:max-w-8xl 2xl:max-w-10xl mx-auto flex-1 flex flex-col">
            <!-- Kanban -->
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm flex flex-col">
                <div class="text-white px-5 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-900 via-sky-900 to-slate-900 ">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-white">Leads Pipeline2: {{ auth()->user()->username }}</h3>
                            <p class="text-sm text-slate-200">This Kanban board displays your personal pipeline of prospects and their current progress through each stage.</p>
                        </div>
                        <span class="inline-flex w-fit items-center rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700">
                            Scoped to user_id #{{ auth()->id() }}
                        </span>
                    </div>
                </div>
                <div class="p-4">
                    @include('prospects.kanban-board')
                </div>
            </div>
            <!-- All Timelines Table with Search, Filter, Pagination -->
            @include('prospects.timeline-table')
        </div>
        <!-- Modal -->
        @include('prospects.timeline-modal')
    </div>
@endsection
