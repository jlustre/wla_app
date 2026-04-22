<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-700 to-cyan-500 text-sm font-bold text-white">
            {{ strtoupper(substr(auth()->user()->username ?? auth()->user()->name ?? 'U', 0, 1)) }}
        </div>
        <div class="hidden text-left md:block">
            <p class="text-sm font-semibold text-teal-50">
                {{ auth()->user()->username ?? auth()->user()->name ?? 'User' }}
            </p>
            <p class="text-xs text-teal-200">
                {{ ucfirst(auth()->user()->role ?? 'Member') }}
            </p>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" class="hidden h-4 w-4 text-slate-400 md:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
        </svg>
    </button>
    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-3 w-72 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl z-50">
        <div class="border-b border-slate-100 px-5 py-4">
            <p class="text-sm font-semibold text-slate-900">
                {{ auth()->user()->username ?? auth()->user()->name ?? 'User' }}
            </p>
            <p class="mt-1 text-sm text-slate-500">
                {{ auth()->user()->email ?? '' }}
            </p>
        </div>
        <div class="p-2">
            <a href="#" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm text-slate-700 hover:bg-slate-50">My Profile</a>
            <a href="#" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm text-slate-700 hover:bg-slate-50">Account Settings</a>
            <a href="#" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm text-slate-700 hover:bg-slate-50">Security</a>
            <a href="#" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm text-slate-700 hover:bg-slate-50">Help Center</a>
            @if(auth()->user() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin')))
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm text-slate-700 hover:bg-slate-50">Admin Dashboard</a>
            @endif
             <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm text-slate-700 hover:bg-slate-50">My Dashboard</a>
            <div class="my-2 border-t border-slate-100"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium text-rose-600 hover:bg-rose-50 w-full text-left">Log Out</button>
            </form>
        </div>
    </div>
</div>