@extends('layouts.admin')

@section('title', 'Add Dashboard Section for ' . $company->name)

@section('content')
<div class="w-full max-w-2xl">
    <h1 class="text-2xl font-extrabold text-cyan-800 mb-6">Add Dashboard Section for <span class="text-cyan-600">{{ $company->name }}</span></h1>
    <form action="{{ route('admin.dashboard-contents.store', $company) }}" method="POST" class="space-y-6 bg-white p-8 rounded-2xl shadow">
        @csrf
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Section Key</label>
                <input type="text" name="section" class="w-full rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500" required placeholder="e.g. hero, overview, products">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Order</label>
                <input type="number" name="order" class="w-full rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500" value="0">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Element Type</label>
                <input type="text" name="element_type" class="w-full rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500" placeholder="e.g. card, section, button">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Element Class</label>
                <input type="text" name="element_class" class="w-full rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500" placeholder="Tailwind classes">
            </div>
        </div>
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Title</label>
            <input type="text" name="title" class="w-full rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
        </div>
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Content (HTML allowed)</label>
            <textarea name="content" rows="6" class="w-full rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500" placeholder="You can use HTML and Tailwind classes here."></textarea>
        </div>
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Image URL</label>
            <input type="text" name="image" class="w-full rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
        </div>
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Meta (JSON)</label>
            <textarea name="meta" rows="2" class="w-full rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500" placeholder='{"style":"background: #fff;"}'></textarea>
        </div>
        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.companies.dashboard-contents.index', $company) }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200">Cancel</a>
            <button type="submit" class="inline-flex items-center px-6 py-2 rounded-xl bg-cyan-600 text-white font-bold shadow hover:bg-cyan-700 transition">Save</button>
        </div>
    </form>
</div>
@endsection
