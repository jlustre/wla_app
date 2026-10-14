@php
    $accentMap = [
        'blue' => 'from-emerald-500/20 to-sky-400/10 text-emerald-700 dark:text-emerald-300',
        'gold' => 'from-yellow-400/25 to-amber-400/10 text-yellow-700 dark:text-yellow-300',
        'emerald' => 'from-emerald-500/20 to-teal-400/10 text-emerald-700 dark:text-emerald-300',
        'slate' => 'from-slate-400/20 to-slate-300/10 text-slate-700 dark:text-slate-300',
    ];
@endphp

<div class="rounded-[20px] border border-slate-200/80 bg-white/90 p-4 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
    <div class="flex items-start justify-between gap-3">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $title }}</p>
            <p class="mt-2 text-2xl font-bold tracking-tight text-slate-950 dark:text-white">{{ $value }}</p>
            @if ($change !== '')
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $change }}</p>
            @endif
        </div>
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br {{ $accentMap[$accent] ?? $accentMap['blue'] }}">
            <x-dashboard-icon :name="$icon" class="h-5 w-5" />
        </span>
    </div>
</div>
