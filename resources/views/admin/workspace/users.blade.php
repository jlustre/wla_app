@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="w-full">
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-teal-600">Admin</p>
        <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-teal-800">User Management</h1>
        <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Review member accounts, assigned roles, and current sponsor links.</p>
    </div>

    <div class="overflow-x-auto rounded-2xl bg-white shadow ring-1 ring-black/5">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Username</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Sponsor</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse ($users as $user)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-semibold text-slate-900">{{ $user->username }}</td>
                        <td class="px-6 py-4 text-slate-700">{{ $user->email }}</td>
                        <td class="px-6 py-4 capitalize text-slate-700">{{ $user->status?->value ?? $user->status }}</td>
                        <td class="px-6 py-4 text-slate-700">{{ $user->getRoleNames()->join(', ') ?: 'member' }}</td>
                        <td class="px-6 py-4 text-slate-700">{{ $user->sponsor?->username ?? 'Unassigned' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $users->links() }}</div>
</div>
@endsection
