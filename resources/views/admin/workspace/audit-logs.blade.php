@extends('layouts.admin')

@section('title', 'Audit Logs')

@section('content')
<div class="w-full">
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-teal-600">Admin</p>
        <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-teal-800">Audit Logs</h1>
        <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Track account, sponsor, and platform changes made by administrators and members.</p>
    </div>

    <div class="overflow-x-auto rounded-2xl bg-white shadow ring-1 ring-black/5">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">When</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Actor</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Event</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse ($logs as $log)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-slate-700">{{ $log->created_at?->format('M d, Y g:i A') }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-900">{{ $log->actor?->username ?? 'System' }}</td>
                        <td class="px-6 py-4 text-slate-700">{{ $log->event }}</td>
                        <td class="px-6 py-4 text-slate-700">{{ class_basename((string) $log->auditable_type) ?: '—' }} {{ $log->auditable_id ?: '' }}</td>
                        <td class="px-6 py-4 text-slate-700">{{ $log->ip_address ?: '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500">No audit activity recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $logs->links() }}</div>
</div>
@endsection
