@php
    $accentMap = [
        'blue' => 'from-blue-600/20 to-cyan-500/10 text-blue-700 dark:text-blue-300',
        'gold' => 'from-amber-400/20 to-orange-400/10 text-amber-700 dark:text-amber-300',
        'emerald' => 'from-emerald-500/20 to-teal-400/10 text-emerald-700 dark:text-emerald-300',
        'slate' => 'from-slate-400/20 to-slate-300/10 text-slate-700 dark:text-slate-300',
    ];
@endphp

<div class="rounded-[28px] border border-slate-200/80 bg-white/90 p-5 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $title }}</p>
            <p class="mt-3 text-3xl font-bold tracking-tight text-slate-950 dark:text-white">{{ $value }}</p>
            @if ($change !== '')
                <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">{{ $change }}</p>
            @endif
        </div>
        <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br {{ $accentMap[$accent] ?? $accentMap['blue'] }}">
            <x-dashboard-icon :name="$icon" class="h-5 w-5" />
        </span>
    </div>
</div>