<div class="flex gap-4 rounded-2xl border p-4 {{ $this->wrapperClasses() }}">
    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl {{ $this->iconClasses() }}">
        @if($activity['type'] === 'success')
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M18 9a6 6 0 11-12 0 6 6 0 0112 0zM5 20a7 7 0 0114 0" />
        </svg>
        @elseif($activity['type'] === 'info')
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M6 10h12M9 13h6M5 17h14" />
        </svg>
        @elseif($activity['type'] === 'danger')
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 9v4m0 4h.01M10.29 3.86l-7.5 13A1 1 0 003.67 18h16.66a1 1 0 00.87-1.5l-7.5-13a1 1 0 00-1.74 0z" />
        </svg>
        @else
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        @endif
    </div>

    <div class="min-w-0">
        <p class="text-sm font-semibold text-slate-900">{{ $activity['title'] }}</p>
        <p class="mt-1 text-sm text-slate-600">{{ $activity['message'] }}</p>
        <p class="mt-2 text-xs font-medium text-slate-400">{{ $activity['time'] }}</p>
    </div>
</div>