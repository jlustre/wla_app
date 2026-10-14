<div class="space-y-6" wire:key="prospect-funnel-root">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-600 dark:text-emerald-300">Workspace</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950 dark:text-white sm:text-3xl">Prospect Funnel</h1>
            <p class="mt-2 max-w-2xl text-sm text-slate-500 dark:text-slate-400">See where every prospect stands &mdash; from first added to joined &mdash; and move them forward as you work them.</p>
        </div>
        <div class="flex shrink-0 flex-wrap gap-2">
            <a href="{{ route('prospects.index') }}" wire:navigate class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                <x-dashboard-icon name="user-plus" class="h-4 w-4" />
                My Prospects
            </a>
        </div>
    </div>

    {{-- Funnel Chart --}}
    <div class="rounded-[28px] border border-slate-200/80 bg-white/90 p-5 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85 sm:p-6">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h2 class="text-sm font-bold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Conversion Funnel</h2>
            <p class="text-xs text-slate-400 dark:text-slate-500">{{ $funnelStats['total'] }} total prospects &bull; <span class="font-semibold text-rose-500">{{ $funnelStats['closed'] }} closed</span></p>
        </div>

        <div class="mt-5 space-y-2.5">
            @foreach ($funnelStats['stages'] as $stageKey => $stat)
                @php
                    $barColor = match ($stat['color']) {
                        'sky' => 'bg-sky-500',
                        'emerald' => 'bg-emerald-500',
                        'amber' => 'bg-amber-500',
                        'indigo' => 'bg-indigo-500',
                        'teal' => 'bg-teal-500',
                        'green' => 'bg-green-500',
                        default => 'bg-slate-500',
                    };
                    $widthPct = max($stat['pct_of_total'], $stat['count'] > 0 ? 4 : 0);
                @endphp
                <div class="flex items-center gap-3">
                    <div class="w-32 shrink-0 text-right text-xs font-semibold text-slate-600 dark:text-slate-300 sm:w-40">{{ $stat['label'] }}</div>
                    <div class="relative flex-1">
                        <div class="h-8 w-full overflow-hidden rounded-lg bg-slate-100 dark:bg-slate-800">
                            <div class="flex h-8 items-center rounded-lg {{ $barColor }} px-3 text-xs font-bold text-white transition-all" style="width: {{ $widthPct }}%">
                                @if ($widthPct > 12)
                                    {{ $stat['count'] }}
                                @endif
                            </div>
                        </div>
                        @if ($widthPct <= 12)
                            <span class="absolute inset-y-0 left-2 flex items-center text-xs font-bold text-slate-700 dark:text-slate-200">{{ $stat['count'] }}</span>
                        @endif
                    </div>
                    <div class="w-28 shrink-0 text-xs text-slate-400 dark:text-slate-500">
                        {{ $stat['pct_of_total'] }}% of total
                        @if ($stat['pct_of_previous'] !== null)
                            <span class="block text-slate-400/80 dark:text-slate-500/80">{{ $stat['pct_of_previous'] }}% of prior</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Search --}}
    <div class="flex items-center gap-3">
        <div class="relative w-full sm:max-w-xs">
            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                <x-dashboard-icon name="magnifying-glass" class="h-4 w-4" />
            </span>
            <input type="text" wire:model.live.debounce.400ms="search" placeholder="Search the board…" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 pl-9 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
        </div>
    </div>

    {{-- Kanban Board --}}
    <div class="overflow-x-auto pb-2" wire:loading.class="opacity-60" wire:target="search">
        <div class="flex min-w-max gap-4">
            @foreach ($stageOptions as $stage => $meta)
                @php
                    $dotColor = match ($meta['color']) {
                        'sky' => 'bg-sky-500',
                        'emerald' => 'bg-emerald-500',
                        'amber' => 'bg-amber-500',
                        'indigo' => 'bg-indigo-500',
                        'teal' => 'bg-teal-500',
                        'green' => 'bg-green-500',
                        'rose' => 'bg-rose-500',
                        default => 'bg-slate-500',
                    };
                @endphp
                <div
                    class="flex w-72 shrink-0 flex-col rounded-[24px] border border-slate-200/80 bg-white/90 shadow-[0_20px_60px_-40px_rgba(15,23,42,0.5)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85"
                    x-data
                    @dragover.prevent
                    @drop.prevent="$wire.requestMove(parseInt($event.dataTransfer.getData('text/plain')), '{{ $stage }}')"
                    wire:key="stage-column-{{ $stage }}"
                >
                    <div class="flex items-center justify-between gap-2 border-b border-slate-200/80 px-4 py-3 dark:border-slate-800">
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 shrink-0 rounded-full {{ $dotColor }}"></span>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">{{ $meta['label'] }}</h3>
                        </div>
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-500 dark:bg-slate-800 dark:text-slate-400">{{ count($board[$stage]) }}</span>
                    </div>

                    <div class="max-h-[32rem] space-y-2.5 overflow-y-auto p-3">
                        @forelse ($board[$stage] as $prospect)
                            <div
                                draggable="true"
                                @dragstart="$event.dataTransfer.setData('text/plain', '{{ $prospect->id }}')"
                                class="cursor-grab rounded-2xl border border-slate-200/80 bg-white p-3 shadow-sm transition hover:shadow-md active:cursor-grabbing dark:border-slate-800 dark:bg-slate-900"
                                wire:key="card-{{ $stage }}-{{ $prospect->id }}"
                            >
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ trim($prospect->first_name.' '.$prospect->last_name) ?: 'Unnamed' }}</p>
                                        <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ $prospect->email ?: ($prospect->phone ?: '—') }}</p>
                                    </div>
                                    @if ($prospect->hotness)
                                        @php
                                            $hotnessClass = match (strtolower((string) $prospect->hotness)) {
                                                'hot' => 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-300',
                                                'warm' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-300',
                                                'cold' => 'bg-sky-50 text-sky-600 dark:bg-sky-500/10 dark:text-sky-300',
                                                default => 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400',
                                            };
                                        @endphp
                                        <span class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $hotnessClass }}">{{ $prospect->hotness }}</span>
                                    @endif
                                </div>

                                <div class="mt-2 flex items-center justify-between gap-2">
                                    <p class="text-[11px] text-slate-400 dark:text-slate-500">
                                        @if ($prospect->latestActivity?->action_at)
                                            {{ $prospect->latestActivity->action_at->diffForHumans() }}
                                        @else
                                            &mdash;
                                        @endif
                                    </p>

                                    <div class="flex items-center gap-1">
                                        <button type="button" wire:click="viewDetails({{ $prospect->id }})" title="View prospect info &amp; activity history" class="inline-flex h-6 w-6 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:bg-slate-50 hover:text-slate-800 dark:border-slate-700 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-100">
                                            <x-dashboard-icon name="clipboard-document-list" class="h-3.5 w-3.5" />
                                        </button>

                                        <div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false">
                                            <button type="button" @click="open = !open" title="Move to another stage" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 px-2 py-1 text-[11px] font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                                                Move
                                                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                                            </button>
                                            <div x-cloak x-show="open" x-transition.origin.top.right @click.outside="open = false" class="absolute right-0 z-20 mt-1.5 w-40 overflow-hidden rounded-xl border border-slate-200 bg-white py-1 shadow-xl dark:border-slate-700 dark:bg-slate-900">
                                                @foreach ($stageOptions as $targetStage => $targetMeta)
                                                    @if ($targetStage !== $stage)
                                                        <button type="button" @click="open = false" wire:click="requestMove({{ $prospect->id }}, '{{ $targetStage }}')" class="block w-full px-3 py-1.5 text-left text-xs font-medium text-slate-600 transition hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">
                                                            {{ $targetMeta['label'] }}
                                                        </button>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="px-1 py-6 text-center text-xs text-slate-400 dark:text-slate-500">No prospects here{{ $search !== '' ? ' matching your search' : '' }}.</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Move-to-stage Modal --}}
    @if ($movingProspectId && $movingToStage && $movingProspect)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm" wire:click.self="cancelMove">
            <div class="w-full max-w-lg rounded-[32px] border border-slate-200/80 bg-white p-6 shadow-2xl dark:border-slate-800 dark:bg-slate-900 sm:p-8">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-300">Move Prospect</p>
                        <h2 class="mt-1 text-xl font-bold text-slate-950 dark:text-white">{{ trim($movingProspect->first_name.' '.$movingProspect->last_name) ?: 'Unnamed' }} &rarr; {{ $stageOptions[$movingToStage]['label'] }}</h2>
                    </div>
                    <button type="button" wire:click="cancelMove" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form wire:submit.prevent="confirmMove" class="mt-6 space-y-4">
                    @if (count($stageOptions[$movingToStage]['actions']) > 1)
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Activity Type</label>
                            <select wire:model="moveAction" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                @foreach ($stageOptions[$movingToStage]['actions'] as $action)
                                    <option value="{{ $action }}">{{ $action }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Date &amp; Time</label>
                        <input type="datetime-local" wire:model="moveDate" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                        @error('moveDate') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Notes</label>
                        <textarea wire:model="moveNotes" rows="2" placeholder="What happened?" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100"></textarea>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Next Follow-up (optional)</label>
                        <input type="datetime-local" wire:model="moveNextFollowUp" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-200/80 pt-5 dark:border-slate-800">
                        <button type="button" wire:click="cancelMove" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                            Cancel
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="confirmMove" class="rounded-xl bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:opacity-60 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200">
                            <span wire:loading.remove wire:target="confirmMove">Confirm Move</span>
                            <span wire:loading wire:target="confirmMove">Saving…</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Prospect Details / Activity History Popup --}}
    @if ($viewingProspectId && $viewingProspect)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm" wire:click.self="closeDetails">
            <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-[32px] border border-slate-200/80 bg-white p-6 shadow-2xl dark:border-slate-800 dark:bg-slate-900 sm:p-8">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-300">Prospect Details</p>
                        <h2 class="mt-1 text-xl font-bold text-slate-950 dark:text-white">{{ trim($viewingProspect->first_name.' '.$viewingProspect->last_name) ?: 'Unnamed' }}</h2>
                    </div>
                    <button type="button" wire:click="closeDetails" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                {{-- Prospect info --}}
                <div class="mt-5 grid gap-3 rounded-2xl border border-slate-200/80 bg-slate-50/60 p-4 text-sm sm:grid-cols-2 dark:border-slate-800 dark:bg-slate-800/40">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Email</p>
                        <p class="text-slate-700 dark:text-slate-200">{{ $viewingProspect->email ?: '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Phone</p>
                        <p class="text-slate-700 dark:text-slate-200">{{ $viewingProspect->phone ?: '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Source</p>
                        <p class="text-slate-700 dark:text-slate-200">{{ $viewingProspect->source ?: '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Hotness</p>
                        <p class="text-slate-700 dark:text-slate-200">{{ $viewingProspect->hotness ?: '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Next Follow-up</p>
                        <p class="text-slate-700 dark:text-slate-200">{{ $viewingProspect->next_follow_up ? $viewingProspect->next_follow_up->format('M d, Y g:ia') : '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Added</p>
                        <p class="text-slate-700 dark:text-slate-200">{{ $viewingProspect->created_at->format('M d, Y') }}</p>
                    </div>
                    @if ($viewingProspect->notes)
                        <div class="sm:col-span-2">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Notes</p>
                            <p class="text-slate-700 dark:text-slate-200">{{ $viewingProspect->notes }}</p>
                        </div>
                    @endif
                </div>

                {{-- Activity history --}}
                <div class="mt-6">
                    <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Activity History ({{ $viewingActivities->count() }})</p>

                    @if ($viewingActivities->isEmpty())
                        <div class="rounded-2xl border border-dashed border-slate-200 p-8 text-center dark:border-slate-700">
                            <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">No activity logged yet</p>
                            <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">Calls, presentations, and other touchpoints will show up here.</p>
                        </div>
                    @else
                        <ul class="space-y-3">
                            @foreach ($viewingActivities as $activity)
                                @php $option = $activityOptions[$activity->action] ?? null; @endphp
                                <li wire:key="view-activity-{{ $activity->id }}" class="flex items-start gap-3 rounded-2xl border border-slate-200/80 bg-white p-4 dark:border-slate-800 dark:bg-slate-900/60">
                                    <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ \App\Models\Timeline::actionBadgeClass($activity->action) }}">
                                        <x-dashboard-icon :name="$option['icon'] ?? 'clock'" class="h-4.5 w-4.5" />
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold {{ \App\Models\Timeline::actionBadgeClass($activity->action) }}">{{ $option['label'] ?? $activity->action }}</span>
                                            <span class="text-xs text-slate-500 dark:text-slate-400">{{ $activity->action_at?->format('M d, Y g:ia') ?? '—' }}</span>
                                        </div>
                                        @if ($activity->notes)
                                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $activity->notes }}</p>
                                        @endif
                                        @if ($activity->next_follow_up_dt)
                                            <p class="mt-2 inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-semibold text-amber-700 dark:bg-amber-500/10 dark:text-amber-300">
                                                Next follow-up: {{ $activity->next_follow_up_dt->format('M d, Y g:ia') }}
                                            </p>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="mt-6 flex items-center justify-end gap-3 border-t border-slate-200/80 pt-5 dark:border-slate-800">
                    <a href="{{ route('prospects.index') }}" wire:navigate class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                        Manage in My Prospects
                    </a>
                    <button type="button" wire:click="closeDetails" class="rounded-xl bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
