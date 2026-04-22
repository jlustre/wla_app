<header id="navbar" class="fixed top-0 w-full z-50 transition-all duration-300 bg-slate-900 bg-opacity-90 border-b border-slate-800 shadow-lg">
    <nav class="container mx-auto px-6 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-3 text-xl md:text-2xl font-bold text-white tracking-tight">
            <a href="/" class="flex items-center space-x-2">
                <img src="/images/brand/squarelogo.png" alt="WLA Square Logo" class="h-10 w-10 mr-2" />
                <span>Wealth Legacy <span class="text-emerald-400">Alliance</span></span>
            </a>
        </div>
        <div class="hidden md:flex md:flex-row items-center space-x-8">
            @include('landing.topnav')
            @include('landing.auth-buttons')
        </div>
        <button id="menu-btn" class="md:hidden text-white focus:outline-none">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
        </button>
    </nav>
    <div id="mobile-menu" class="hidden md:hidden bg-slate-900 bg-opacity-95 border-t border-slate-800 px-6 py-8 space-y-6">
        @yield('mobile-nav')
        @yield('mobile-auth-buttons')
    </div>
</header>