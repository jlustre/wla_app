<form method="POST" action="{{ route('logout') }}" x-data @submit.prevent="if (confirm('Log out of your member dashboard?')) { $el.submit(); }">
    @csrf
    <button type="submit" class="flex w-full items-center justify-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
        <x-dashboard-icon name="arrow-right-on-rectangle" class="h-4 w-4" />
        <span>Logout</span>
    </button>
</form>