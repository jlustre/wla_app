@extends('layouts.admin')

@section('title', 'Sponsor Relationships')

@section('content')
<div class="w-full">
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-teal-600">Admin</p>
        <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-teal-800">Sponsor Relationships</h1>
        <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Inspect current and historical sponsor assignments across the member network.</p>
    </div>

    <div class="overflow-x-auto rounded-2xl bg-white shadow ring-1 ring-black/5">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Member</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Sponsor</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Sponsor Code</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Linked</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse ($relationships as $relationship)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-semibold text-slate-900">{{ $relationship->member?->username ?? 'Unknown member' }}</td>
                        <td class="px-6 py-4 text-slate-700">{{ $relationship->sponsor?->username ?? 'Unassigned' }}</td>
                        <td class="px-6 py-4 text-slate-700">{{ $relationship->sponsor_code ?: '—' }}</td>
                        <td class="px-6 py-4 text-slate-700">{{ $relationship->is_current ? 'Current' : 'Ended' }}</td>
                        <td class="px-6 py-4 text-slate-700">{{ optional($relationship->linked_at)->format('M d, Y') ?? 'Not recorded' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500">No sponsor relationships found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $relationships->links() }}</div>
</div>
@endsection
