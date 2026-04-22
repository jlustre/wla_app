<nav class="w-full bg-white border-b shadow flex items-center justify-between px-6 h-16">
    <!-- Left: App/Logo -->
    <div class="flex items-center gap-3">
        <span class="font-bold text-lg text-gray-800">WLA</span>
    </div>
    <!-- Right: All Menus -->
    <div class="flex items-center gap-6 ml-auto">
        <!-- Notifications -->
        <button class="relative focus:outline-none">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-500 rounded-full"></span>
        </button>
        <!-- Username -->
        <span class="text-gray-700 font-medium">{{ auth()->user()->username }}</span>
        <!-- Avatar Dropdown -->
        <div class="relative group">
            <button class="focus:outline-none">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->check() ? auth()->user()->username : 'Guest') }}&background=4F46E5&color=fff"
                    class="w-8 h-8 rounded-full border-2 border-indigo-500" alt="Avatar">
            </button>
            <div class="hidden group-hover:block absolute right-0 mt-0 w-40 bg-white border rounded shadow-lg z-50">
                <a href="{{ route('profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Log
                        Out</button>
                </form>
            </div>
        </div>
    </div>
</nav>