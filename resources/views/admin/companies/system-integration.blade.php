<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 px-5 py-4 md:px-6">
        <div class="flex items-start gap-3">
        <div class="mt-0.5 flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-100 text-cyan-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h10" />
            </svg>
        </div>
        <div>
            <h2 class="text-lg font-semibold text-slate-900">System Integration (WLA Logic)</h2>
            <p class="mt-1 text-sm text-slate-500">Control how the company behaves within the WLA platform.</p>
        </div>
        </div>
    </div>

    <div class="space-y-5 px-5 py-5 md:px-6">
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Priority Rank</label>
            <input type="number" placeholder="1" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100" />
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Status</label>
            <select class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100">
            <option>Active</option>
            <option>Inactive</option>
            <option>Draft</option>
            </select>
        </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <label class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
            <div>
            <p class="text-sm font-semibold text-slate-800">Enable in Platform</p>
            <p class="mt-1 text-xs text-slate-500">Display this company across WLA dashboards.</p>
            </div>
            <button type="button" class="relative inline-flex h-7 w-12 items-center rounded-full bg-sky-600 transition">
            <span class="inline-block h-5 w-5 translate-x-6 rounded-full bg-white shadow"></span>
            </button>
        </label>

        <label class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
            <div>
            <p class="text-sm font-semibold text-slate-800">Eligible for Spillover</p>
            <p class="mt-1 text-xs text-slate-500">Allow the WLA spillover logic for this company.</p>
            </div>
            <button type="button" class="relative inline-flex h-7 w-12 items-center rounded-full bg-sky-600 transition">
            <span class="inline-block h-5 w-5 translate-x-6 rounded-full bg-white shadow"></span>
            </button>
        </label>
        </div>
    </div>
    </div>