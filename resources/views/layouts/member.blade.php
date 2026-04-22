<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>@yield('title', 'WLA Dashboard')</title>
        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Alpine.js CDN -->
        <script
            defer
            src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"
        ></script>
    </head>
    <body class="overflow-x-hidden">
        <div
            x-data="{ sidebarOpen: false, notificationsOpen: false, profileOpen: false, networkOpen: false, prospectsOpen: false, companiesOpen: false, trainingOpen: false, calendarOpen: false, earningsOpen: false, reportsOpen: false, settingsOpen: false }"
            class="min-h-screen bg-slate-50 text-slate-800 overflow-x-hidden"
        >
            <!-- App Shell -->
            <div class="flex min-h-screen">
                <!-- Mobile Sidebar Backdrop -->
                @include('dashboard.mobile-sidebar-backdrop')

                <!-- Sidebar -->
                @include('layouts.partials.left-sidebar')

                <!-- Main Content -->
                <div class="flex min-h-screen flex-1 flex-col lg:pl-72">
                    <!-- Top Header -->
                   @include('layouts.partials.top-header')
                   <div class="p-2 lg:p-4"> 
                   @yield('content')
                   </div>
                    
                </div>
                            
            </div>
        </div>
        <!-- Go to Top Button -->
        <x-go-to-top />
        <!-- Width Indicator removed to prevent extra horizontal scroll bar -->
    </body>
</html>