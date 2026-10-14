<div class="w-full space-y-6" wire:key="admin-insights-manager-root">

    {{-- Header --}}
    <div>
        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-600">Admin</p>
        <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-teal-800">AI Insights</h1>
        <p class="mt-1 max-w-2xl text-sm text-slate-500">Automated signals detected from your platform's own data — sponsor coaching, risk flags, and education gaps surfaced without manual digging. (This app has no language-model integration; these are rule-based checks over real records, not generated text.)</p>
    </div>

    {{-- Severity summary --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <button type="button" wire:click="$set('severityFilter', 'critical')" class="rounded-2xl bg-white p-5 text-left shadow ring-1 ring-black/5 transition hover:ring-rose-300 {{ $severityFilter === 'critical' ? 'ring-2 ring-rose-500' : '' }}">
            <p class="text-xs font-bold uppercase tracking-wider text-rose-500">Critical</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ $counts['critical'] }}</p>
        </button>
        <button type="button" wire:click="$set('severityFilter', 'warning')" class="rounded-2xl bg-white p-5 text-left shadow ring-1 ring-black/5 transition hover:ring-amber-300 {{ $severityFilter === 'warning' ? 'ring-2 ring-amber-500' : '' }}">
            <p class="text-xs font-bold uppercase tracking-wider text-amber-500">Warning</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ $counts['warning'] }}</p>
        </button>
        <button type="button" wire:click="$set('severityFilter', 'info')" class="rounded-2xl bg-white p-5 text-left shadow ring-1 ring-black/5 transition hover:ring-sky-300 {{ $severityFilter === 'info' ? 'ring-2 ring-sky-500' : '' }}">
            <p class="text-xs font-bold uppercase tracking-wider text-sky-500">Informational</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ $counts['info'] }}</p>
        </button>
        <button type="button" wire:click="$set('severityFilter', 'good')" class="rounded-2xl bg-white p-5 text-left shadow ring-1 ring-black/5 transition hover:ring-green-300 {{ $severityFilter === 'good' ? 'ring-2 ring-green-500' : '' }}">
            <p class="text-xs font-bold uppercase tracking-wider text-green-600">All Clear</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ $counts['good'] }}</p>
        </button>
    </div>

    @if ($severityFilter !== '')
        <button type="button" wire:click="$set('severityFilter', '')" class="text-xs font-semibold text-teal-700 hover:underline">&larr; Clear filter, show all insights</button>
    @endif

    {{-- Insight cards --}}
    <div class="grid gap-4 lg:grid-cols-2">
        @forelse ($insights as $insight)
            @php
                $styles = match ($insight['severity']) {
                    'critical' => ['badge' => 'bg-rose-100 text-rose-700', 'ring' => 'ring-rose-200', 'dot' => 'bg-rose-500', 'label' => 'Critical'],
                    'warning' => ['badge' => 'bg-amber-100 text-amber-700', 'ring' => 'ring-amber-200', 'dot' => 'bg-amber-500', 'label' => 'Warning'],
                    'info' => ['badge' => 'bg-sky-100 text-sky-700', 'ring' => 'ring-sky-200', 'dot' => 'bg-sky-500', 'label' => 'Informational'],
                    default => ['badge' => 'bg-green-100 text-green-700', 'ring' => 'ring-green-200', 'dot' => 'bg-green-500', 'label' => 'All Clear'],
                };
            @endphp
            <div class="rounded-2xl bg-white p-5 shadow ring-1 {{ $styles['ring'] }}">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">{{ $insight['category'] }}</p>
                        <h2 class="mt-0.5 text-base font-extrabold text-teal-900">{{ $insight['title'] }}</h2>
                    </div>
                    <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-bold uppercase {{ $styles['badge'] }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $styles['dot'] }}"></span>
                        {{ $styles['label'] }}
                    </span>
                </div>

                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-teal-800">{{ number_format($insight['metric']) }}</span>
                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $insight['metric_label'] }}</span>
                </div>

                <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $insight['detail'] }}</p>

                @if (! empty($insight['breakdown']))
                    <div class="mt-4 space-y-2">
                        @foreach ($insight['breakdown'] as $row)
                            <div class="flex items-center gap-3">
                                <div class="w-36 shrink-0 truncate text-xs font-semibold text-slate-600">{{ $row['label'] }}</div>
                                <div class="relative flex-1">
                                    <div class="h-4 w-full overflow-hidden rounded-lg bg-slate-100">
                                        <div class="h-4 rounded-lg bg-indigo-400" style="width: {{ max($row['pct'], $row['completed'] > 0 ? 4 : 0) }}%"></div>
                                    </div>
                                </div>
                                <div class="w-10 shrink-0 text-right text-[11px] text-slate-400">{{ $row['pct'] }}%</div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if (! empty($insight['cta_route']))
                    <div class="mt-4 border-t border-slate-100 pt-3">
                        <a href="{{ $insight['cta_route'] }}" wire:navigate class="text-xs font-semibold text-teal-700 hover:underline">{{ $insight['cta_label'] }} &rarr;</a>
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-2 rounded-2xl bg-white p-10 text-center shadow ring-1 ring-black/5">
                <p class="text-sm font-semibold text-slate-600">No insights at this severity level.</p>
            </div>
        @endforelse
    </div>
</div>
