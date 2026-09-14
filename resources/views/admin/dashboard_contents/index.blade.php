@extends('layouts.admin')

@section('title', 'Manage Dashboard Sections for ' . $company->name)

@section('content')
<div class="w-full max-w-5xl">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-extrabold text-teal-800">Dashboard Sections for <span class="text-teal-600">{{ $company->name }}</span></h1>
        <a href="{{ route('admin.dashboard-contents.create', $company) }}" class="inline-flex items-center px-4 py-2 bg-cyan-600 text-white rounded-xl font-semibold shadow hover:bg-cyan-700 transition">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Section
        </a>
    </div>
    @if(session('success'))
        <div class="mb-4 p-4 rounded-xl bg-green-100 text-green-800 font-semibold">{{ session('success') }}</div>
    @endif
    <div class="overflow-x-auto bg-white rounded-2xl shadow ring-1 ring-black/5">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Section</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Order</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100">
                @foreach($contents as $content)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-cyan-900">{{ $content->section }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-slate-700">{{ $content->title }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-slate-700">{{ $content->order }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-slate-700">{{ $content->element_type }}</td>
                    <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                        <a href="{{ route('admin.dashboard-contents.edit', [$company, $content]) }}" class="inline-flex items-center px-3 py-1 rounded-lg bg-blue-50 text-blue-700 font-semibold text-xs hover:bg-blue-100">Edit</a>
                        @if($content->deleted_at)
                            <form action="{{ route('admin.dashboard-contents.restore', [$company->id, $content->id]) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-1 rounded-lg bg-green-50 text-green-700 font-semibold text-xs hover:bg-green-100">Restore</button>
                            </form>
                        @else
                            <form action="{{ route('admin.dashboard-contents.destroy', [$company, $content]) }}" method="POST" class="inline" onsubmit="return confirm('Delete this section?')">
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
    <div class="mt-8">
        <a href="{{ route('admin.companies.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200">Back to Companies</a>
    </div>
</div>
@endsection
