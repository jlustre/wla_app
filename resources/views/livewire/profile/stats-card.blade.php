<div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 transition hover:bg-white hover:shadow-md">
    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">{{ $label }}</p>
    <p class="mt-2 text-2xl font-bold text-slate-900">{{ $value }}</p>
    @if($subtext)
    <p class="mt-1 text-xs text-slate-500">{{ $subtext }}</p>
    @endif
</div>