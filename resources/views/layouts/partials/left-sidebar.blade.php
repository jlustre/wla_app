<!-- Mobile Sidebar -->
<aside
    x-show="sidebarOpen"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="-translate-x-full opacity-0"
    x-transition:enter-end="translate-x-0 opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="translate-x-0 opacity-100"
    x-transition:leave-end="-translate-x-full opacity-0"
    class="fixed inset-y-0 left-0 z-40 w-72 border-r border-slate-200 bg-teal-800 flex-col flex lg:hidden"
    style="display: none"
>
    <!-- Fixed Brand -->
    <div id="brand" class="flex h-20 items-center gap-4 border-b border-teal-200 px-6 flex-shrink-0 bg-teal-800 text-white z-50">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-600 to-blue-500 text-lg font-bold text-white shadow-lg shadow-indigo-200">W</div>
        <div>
            <h1 class="text-base font-bold text-white">Wealth Legacy Alliance</h1>
            <p class="text-sm text-teal-300">Member Dashboard</p>
        </div>
    </div>
    <!-- Scrollable Admin Menu -->
    <div class="flex-1 overflow-y-auto px-2 py-0">
        @include('layouts.partials.member-menu')
    </div>
</aside>

<!-- Desktop Sidebar -->
<aside class="fixed inset-y-0 left-0 z-40 w-72 border-r border-slate-200 bg-teal-800 flex-col hidden lg:flex">
    <!-- Fixed Brand -->
    <div id="brand" class="flex h-20 items-center gap-4 border-b border-teal-200 px-6 flex-shrink-0 bg-teal-800 text-white z-50">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-600 to-blue-500 text-lg font-bold text-white shadow-lg shadow-indigo-200">W</div>
        <div>
            <h1 class="text-base font-bold text-white">Wealth Legacy Alliance</h1>
            <p class="text-sm text-teal-300">Member Dashboard</p>
        </div>
    </div>
    <!-- Fixed Brand -->
    <div id="brand" class="flex h-20 items-center gap-4 border-b border-teal-200 px-6 flex-shrink-0 bg-teal-800 text-white z-50 xl:top-0 xl:left-0 xl:w-72 xl:fixed xl:z-50">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-600 to-blue-500 text-lg font-bold text-white shadow-lg shadow-indigo-200">W</div>
        <div>
            <h1 class="text-base font-bold text-white">Wealth Legacy Alliance</h1>
            <p class="text-sm text-teal-300">Member Dashboard</p>
        </div>
    </div>
    <!-- Scrollable Admin Menu -->
    <div class="flex-1 overflow-y-auto px-2 py-0">
        @include('layouts.partials.member-menu')
    </div>
    <!-- Fixed Sidebar Footer -->
    {{-- <div class="w-full xl:fixed xl:bottom-0 xl:left-0 xl:w-72 bg-teal-800 z-50">
        @include('layouts.partials.sidebar-footer')
    </div> --}}
</aside>
