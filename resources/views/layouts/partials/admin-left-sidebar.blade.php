<aside class="fixed inset-y-0 left-0 z-40 hidden w-72 border-r border-slate-200 text-white bg-slate-800 xl:flex xl:flex-col">
    <!-- Fixed Brand -->
    <div id="brand" class="flex h-20 items-center gap-4 border-b border-slate-200 bg-teal-800 px-6 flex-shrink-0 z-50 xl:static xl:top-0 xl:left-0 xl:w-72 xl:fixed xl:z-50">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-600 to-blue-500 text-lg font-bold text-white shadow-lg shadow-indigo-200">W</div>
        <div>
            <h1 class="text-base font-bold text-white">Wealth Legacy Alliance</h1>
            <p class="text-sm text-slate-300">Admin Dashboard</p>
        </div>
    </div>
    <!-- Scrollable Admin Menu -->
    <div class="flex-1 overflow-y-auto px-0 py-0" style="margin-top: 5rem;">
        @include('layouts.partials.admin-menu')
    </div>
    <!-- Fixed Sidebar Footer -->
    {{-- <div class="w-full xl:fixed xl:bottom-0 xl:left-0 xl:w-72 bg-slate-800 z-50"> --}}
        {{-- @include('layouts.partials.admin-sidebar-footer') --}}
    {{-- </div> --}}
</aside>