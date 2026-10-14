@php
    $toneMap = [
        'blue' => 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-500/20',
        'gold' => 'bg-yellow-50 text-yellow-800 ring-yellow-200 dark:bg-yellow-400/10 dark:text-yellow-300 dark:ring-yellow-400/20',
        'emerald' => 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-500/20',
        'slate' => 'bg-slate-100 text-slate-700 ring-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700',
    ];
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $toneMap[$tone] ?? $toneMap['blue'] }}">
    {{ $label }}
</span>
