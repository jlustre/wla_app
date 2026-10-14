@extends('layouts.admin')

@section('title', 'Manage Companies')

@section('content')
<div class="w-full">
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

    <form method="GET" action="{{ route('admin.companies.index') }}" class="mb-6 flex flex-wrap items-end gap-3 rounded-2xl bg-white p-4 shadow ring-1 ring-black/5">
        <div class="min-w-[200px] flex-1">
            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Search</label>
            <input type="text" name="search" value="{{ $search }}" placeholder="Name or category…" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
        </div>
        <div class="min-w-[160px]">
            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Status</label>
            <select name="status" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                <option value="" @selected($status === '')>All Status</option>
                <option value="active" @selected($status === 'active')>Active</option>
                <option value="inactive" @selected($status === 'inactive')>Inactive</option>
                <option value="deleted" @selected($status === 'deleted')>Deleted</option>
            </select>
        </div>
        <div class="min-w-[160px]">
            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Category</label>
            <select name="category" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                <option value="">All Categories</option>
                @foreach ($categories as $categoryOption)
                    <option value="{{ $categoryOption }}" @selected($category === $categoryOption)>{{ $categoryOption }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="inline-flex items-center rounded-xl bg-teal-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-teal-700">Filter</button>
            @if ($search !== '' || $status !== '' || $category !== '')
                <a href="{{ route('admin.companies.index') }}" class="inline-flex items-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">Reset</a>
            @endif
        </div>
    </form>

    <div class="overflow-x-auto bg-white rounded-2xl shadow ring-1 ring-black/5">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Rating</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Published</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Colors</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100">
                @forelse($companies as $company)
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
                        @elseif((int) $company->status === 1)
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold uppercase">Active</span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold uppercase">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($company->reviews_count > 0)
                            <div class="flex items-center gap-1.5">
                                <x-star-rating :rating="$company->average_rating" size="text-xs" base-color="text-slate-200" />
                                <span class="text-xs font-semibold text-slate-600">{{ number_format($company->average_rating, 1) }}</span>
                                <span class="text-xs text-slate-400">({{ $company->reviews_count }})</span>
                            </div>
                        @else
                            <span class="text-xs text-slate-400">No reviews</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($company->deleted_at)
                            <span class="text-xs text-slate-400">&mdash;</span>
                        @else
                            <form action="{{ route('admin.companies.toggle-publish', $company) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase transition {{ $company->is_publish ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                    {{ $company->is_publish ? 'Published' : 'Draft' }}
                                </button>
                            </form>
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
                        <a href="{{ route('admin.companies.edit', $company) }}" class="inline-flex items-center px-3 py-1 rounded-lg bg-emerald-50 text-emerald-700 font-semibold text-xs hover:bg-emerald-100">Edit</a>
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
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-sm text-slate-500">No companies match your filters.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $companies->links() }}</div>
</div>
@endsection
