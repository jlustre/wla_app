@if(auth()->user() && auth()->user()->hasRole('admin'))
<div class=" border border-slate-200 bg-white p-5 shadow-sm">
<div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
<div>
    <h2 class="text-lg font-bold text-slate-900">Admin Member Workspace Switcher</h2>
    <p class="text-sm text-slate-500">
    Search a member and load the exact prospect workspace for that selected user_id.
    </p>
</div>

<div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
    <div>
    <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Search Member</label>
    <input
        type="text"
        placeholder="Search by name, email, or user ID..."
        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
    />
    </div>

    <div>
    <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Selected Member</label>
    <select class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100">
        <option>Joey Lustre (#152)</option>
        <option>John Doe (#153)</option>
        <option>Maria Santos (#154)</option>
    </select>
    </div>

    <div>
    <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">View Mode</label>
    <select class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100">
        <option>Scoped Prospect Workspace</option>
        <option>Read Only</option>
        <option>Editable Admin Support View</option>
    </select>
    </div>

    <div>
    <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Action</label>
    <button class="w-full rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
        Load Workspace
    </button>
    </div>
</div>
</div>
</div>
@endif