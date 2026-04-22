@extends('layouts.admin')

@section('title', 'Manage Companies')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-extrabold text-teal-800">Companies</h1>
        <a href="{{ route('admin.companies.create') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-xl font-semibold shadow hover:bg-teal-700 transition">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Company
        </a>
    </div>
    @if(session('success'))
        <div class="mb-4 p-4 rounded-xl bg-green-100 text-green-800 font-semibold">{{ session('success') }}</div>
    @endif
    <div class="overflow-x-auto bg-white rounded-2xl shadow ring-1 ring-black/5">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Colors</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100">
                @foreach($companies as $company)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-teal-900 flex items-center gap-2">
                        @if($company->logo)
                            <img src="{{ asset('storage/' . $company->logo) }}" class="h-8 w-8 rounded-full object-cover border border-slate-200" alt="Logo">
                        @else
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-slate-200 text-teal-700 font-bold">{{ strtoupper(substr($company->name,0,1)) }}</span>
                        @endif
                        {{ $company->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-slate-700">{{ $company->category }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($company->deleted_at)
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold uppercase">Deleted</span>
                        @elseif($company->status === 'active')
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold uppercase">Active</span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold uppercase">{{ ucfirst($company->status) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex gap-1">
                            @foreach(['primary_color','secondary_color','highlight_color','background_color'] as $color)
                                @if($company->$color)
                                    <span class="inline-block w-5 h-5 rounded-full border border-slate-200" style="background: {{ $company->$color }}" title="{{ $color }}"></span>
                                @endif
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                        <a href="{{ route('admin.companies.edit', $company) }}" class="inline-flex items-center px-3 py-1 rounded-lg bg-blue-50 text-blue-700 font-semibold text-xs hover:bg-blue-100">Edit</a>
                        <a href="{{ route('admin.companies.dashboard-contents.index', $company) }}" class="inline-flex items-center px-3 py-1 rounded-lg bg-cyan-50 text-cyan-700 font-semibold text-xs hover:bg-cyan-100">Sections</a>
                        @if($company->deleted_at)
                            <form action="{{ route('admin.companies.restore', $company->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-1 rounded-lg bg-green-50 text-green-700 font-semibold text-xs hover:bg-green-100">Restore</button>
                            </form>
                        @else
                            <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" class="inline" onsubmit="return confirm('Delete this company?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1 rounded-lg bg-red-50 text-red-700 font-semibold text-xs hover:bg-red-100">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
