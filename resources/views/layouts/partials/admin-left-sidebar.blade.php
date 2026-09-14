<aside x-cloak class="fixed inset-y-0 left-0 z-50 w-72 border-r border-slate-200 bg-slate-800 text-white shadow-2xl transition duration-300 lg:hidden" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    <div class="flex h-20 items-center gap-4 border-b border-slate-700 bg-teal-800 px-6">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-600 to-blue-500 text-lg font-bold text-white shadow-lg shadow-indigo-200">W</div>
        <div>
            <h1 class="text-base font-bold text-white">Wealth Legacy Alliance</h1>
            <p class="text-sm text-slate-300">Admin Dashboard</p>
        </div>
    </div>
    <div class="h-[calc(100vh-5rem)] overflow-y-auto">
        @include('layouts.partials.admin-menu')
    </div>
</aside>

<aside class="fixed inset-y-0 left-0 z-50 hidden w-72 border-r border-slate-200 bg-slate-800 text-white lg:flex lg:flex-col">
    <div class="flex h-20 flex-shrink-0 items-center gap-4 border-b border-slate-200 bg-teal-800 px-6">
        <a href="{{ \App\Support\Nav::route('admin.dashboard') }}" class="flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-600 to-blue-500 text-lg font-bold text-white shadow-lg shadow-indigo-200">W</div>
            <div>
                <h1 class="text-base font-bold text-white">Wealth Legacy Alliance</h1>
                <p class="text-sm text-slate-300">Admin Dashboard</p>
            </div>
        </a>
    </div>
    <div class="flex-1 overflow-y-auto">
        @include('layouts.partials.admin-menu')
    </div>
</aside>
