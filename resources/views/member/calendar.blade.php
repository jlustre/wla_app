@extends('layouts.member')

@section('title', 'My Calendar')

@push('head')
    @vite(['resources/js/calendar.js'])
@endpush

@section('content')
    <div class="space-y-6">
        <livewire:member.calendar-manager />

        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-600 dark:text-emerald-300">Lists</p>
            <h2 class="mt-1 text-xl font-bold text-slate-950 dark:text-white">Calls, Follow-ups, Tasks &amp; Events</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Everything on your calendar, broken out by type — search, filter by date, and page through each list independently.</p>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <livewire:member.calendar-event-list type="call" heading="Scheduled Phone Calls" icon="phone" :key="'calendar-list-call'" />
            <livewire:member.calendar-event-list type="follow_up" heading="Follow-ups" icon="arrow-path" :key="'calendar-list-follow_up'" />
            <livewire:member.calendar-event-list type="task" heading="Task List" icon="check-circle" :key="'calendar-list-task'" />
            <livewire:member.calendar-event-list type="event" heading="Events" icon="calendar" :key="'calendar-list-event'" />
        </div>
    </div>
@endsection
