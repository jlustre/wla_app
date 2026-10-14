<div class="w-full space-y-6" wire:key="admin-pipeline-manager-root">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-teal-800">Leads Pipeline</h1>
            <p class="mt-1 text-sm text-slate-500">Every prospect across every member, bucketed by stage. Drag a card (or use Move) to resort it.</p>
        </div>
        <button type="button" wire:click="openCreate" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-xl font-semibold shadow hover:bg-teal-700 transition">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add to Pipeline
        </button>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-xl bg-green-100 text-green-800 font-semibold">{{ session('success') }}</div>
    @endif

    {{-- Stats --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Prospects</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
            <p class="text-xs font-bold uppercase tracking-wider text-rose-500">Hot Leads</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['hot']) }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
            <p class="text-xs font-bold uppercase tracking-wider text-amber-500">Follow-ups Due (7d)</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['due_this_week']) }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
            <p class="text-xs font-bold uppercase tracking-wider text-teal-600">Added This Week</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($stats['added_this_week']) }}</p>
        </div>
    </div>

    {{-- Conversion Funnel --}}
    <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5 sm:p-6">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500">Conversion Funnel</h2>
            <p class="text-xs text-slate-400">{{ $funnelStats['total'] }} total &bull; <span class="font-semibold text-rose-500">{{ $funnelStats['closed'] }} closed</span></p>
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
                    <div class="w-28 shrink-0 text-right text-xs font-semibold text-slate-600 sm:w-36">{{ $stat['label'] }}</div>
                    <div class="relative flex-1">
                        <div class="h-7 w-full overflow-hidden rounded-lg bg-slate-100">
                            <div class="flex h-7 items-center rounded-lg {{ $barColor }} px-3 text-xs font-bold text-white transition-all" style="width: {{ $widthPct }}%">
                                @if ($widthPct > 12)
                                    {{ $stat['count'] }}
                                @endif
                            </div>
                        </div>
                        @if ($widthPct <= 12)
                            <span class="absolute inset-y-0 left-2 flex items-center text-xs font-bold text-slate-700">{{ $stat['count'] }}</span>
                        @endif
                    </div>
                    <div class="w-24 shrink-0 text-xs text-slate-400">
                        {{ $stat['pct_of_total'] }}% of total
                        @if ($stat['pct_of_previous'] !== null)
                            <span class="block text-slate-400/80">{{ $stat['pct_of_previous'] }}% of prior</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Filters --}}
    <div class="rounded-2xl bg-white p-4 shadow ring-1 ring-black/5">
        <div class="grid gap-3 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Search</label>
                <input type="text" wire:model.live.debounce.400ms="search" placeholder="Name, email, phone…" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
            </div>
            <div class="lg:col-span-3">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Owner</label>
                <select wire:model.live="ownerFilter" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    <option value="">All owners</option>
                    @foreach ($filterOptions['owners'] as $owner)
                        <option value="{{ $owner->id }}">{{ $owner->username }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-2">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Source</label>
                <select wire:model.live="sourceFilter" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    <option value="">All sources</option>
                    @foreach ($filterOptions['sources'] as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-2">
                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Hotness</label>
                <select wire:model.live="hotnessFilter" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    <option value="">All</option>
                    @foreach ($filterOptions['hotness'] as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end lg:col-span-1">
                <button type="button" wire:click="clearFilters" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                    Clear
                </button>
            </div>
        </div>
    </div>

    {{-- Kanban Board --}}
    <div class="overflow-x-auto pb-2" wire:loading.class="opacity-60" wire:target="search,ownerFilter,sourceFilter,hotnessFilter,remove">
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
                    $col = $board[$stage];
                @endphp
                <div
                    class="flex w-80 shrink-0 flex-col rounded-2xl bg-white shadow ring-1 ring-black/5"
                    x-data
                    @dragover.prevent
                    @drop.prevent="$wire.requestMove(parseInt($event.dataTransfer.getData('text/plain')), '{{ $stage }}')"
                    wire:key="stage-column-{{ $stage }}"
                >
                    <div class="flex items-center justify-between gap-2 border-b border-slate-200 px-4 py-3">
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 shrink-0 rounded-full {{ $dotColor }}"></span>
                            <h3 class="text-sm font-bold text-teal-900">{{ $meta['label'] }}</h3>
                        </div>
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-500">{{ $col['total'] }}</span>
                    </div>

                    <div class="min-h-[8rem] space-y-2.5 p-3">
                        @forelse ($col['items'] as $prospect)
                            <div
                                draggable="true"
                                @dragstart="$event.dataTransfer.setData('text/plain', '{{ $prospect->id }}')"
                                class="cursor-grab rounded-xl border border-slate-200 bg-white p-3 shadow-sm transition hover:shadow-md active:cursor-grabbing"
                                wire:key="card-{{ $stage }}-{{ $prospect->id }}"
                            >
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-teal-900">{{ trim($prospect->first_name.' '.$prospect->last_name) ?: 'Unnamed' }}</p>
                                        <p class="truncate text-xs text-slate-500">{{ $prospect->email ?: ($prospect->phone ?: '—') }}</p>
                                        <p class="mt-0.5 truncate text-[11px] font-semibold uppercase tracking-wide text-slate-400">{{ $prospect->user?->username ?? 'Unassigned' }}</p>
                                    </div>
                                    @if ($prospect->hotness)
                                        @php
                                            $hotnessClass = match (strtolower((string) $prospect->hotness)) {
                                                'hot' => 'bg-rose-100 text-rose-700',
                                                'warm' => 'bg-amber-100 text-amber-700',
                                                'cold' => 'bg-sky-100 text-sky-700',
                                                default => 'bg-slate-100 text-slate-500',
                                            };
                                        @endphp
                                        <span class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $hotnessClass }}">{{ $prospect->hotness }}</span>
                                    @endif
                                </div>

                                <div class="mt-2 flex items-center justify-between gap-2">
                                    <p class="text-[11px] text-slate-400">
                                        @if ($prospect->latestActivity?->action_at)
                                            {{ $prospect->latestActivity->action_at->diffForHumans() }}
                                        @else
                                            &mdash;
                                        @endif
                                    </p>

                                    <div class="flex items-center gap-1">
                                        <button type="button" wire:click="viewDetails({{ $prospect->id }})" title="View" class="inline-flex h-6 w-6 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:bg-slate-50 hover:text-teal-700">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        </button>
                                        <button type="button" wire:click="confirmRemove({{ $prospect->id }})" title="Remove from pipeline" class="inline-flex h-6 w-6 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:bg-rose-50 hover:text-rose-600">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>

                                        <div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false">
                                            <button type="button" @click="open = !open" title="Move to another stage" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 px-2 py-1 text-[11px] font-semibold text-slate-600 transition hover:bg-slate-50">
                                                Move
                                                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                                            </button>
                                            <div x-cloak x-show="open" x-transition.origin.top.right @click.outside="open = false" class="absolute right-0 z-20 mt-1.5 w-40 overflow-hidden rounded-xl border border-slate-200 bg-white py-1 shadow-xl">
                                                @foreach ($stageOptions as $targetStage => $targetMeta)
                                                    @if ($targetStage !== $stage)
                                                        <button type="button" @click="open = false" wire:click="requestMove({{ $prospect->id }}, '{{ $targetStage }}')" class="block w-full px-3 py-1.5 text-left text-xs font-medium text-slate-600 transition hover:bg-slate-50">
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
                            <p class="px-1 py-6 text-center text-xs text-slate-400">No prospects here{{ $search !== '' ? ' matching your filters' : '' }}.</p>
                        @endforelse
                    </div>

                    @if ($col['lastPage'] > 1)
                        <div class="flex items-center justify-between border-t border-slate-200 px-3 py-2">
                            <button type="button" wire:click="goToStagePage('{{ $stage }}', {{ $col['page'] - 1 }})" @disabled($col['page'] <= 1) class="rounded-lg px-2 py-1 text-xs font-semibold text-slate-500 hover:bg-slate-50 disabled:opacity-30">
                                &larr; Prev
                            </button>
                            <span class="text-[11px] text-slate-400">Page {{ $col['page'] }} / {{ $col['lastPage'] }}</span>
                            <button type="button" wire:click="goToStagePage('{{ $stage }}', {{ $col['page'] + 1 }})" @disabled($col['page'] >= $col['lastPage']) class="rounded-lg px-2 py-1 text-xs font-semibold text-slate-500 hover:bg-slate-50 disabled:opacity-30">
                                Next &rarr;
                            </button>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Add to Pipeline Modal --}}
    @if ($showFormModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4" wire:click.self="closeModal">
            <div class="max-h-[90vh] w-full max-w-xl overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl sm:p-8">
                <div class="flex items-start justify-between">
                    <h2 class="text-xl font-extrabold text-teal-800">Add to Pipeline</h2>
                    <button type="button" wire:click="closeModal" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="mt-6 space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Owner</label>
                            <select wire:model="user_id" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                                <option value="">Select owner…</option>
                                @foreach ($allOwners as $owner)
                                    <option value="{{ $owner->id }}">{{ $owner->username }}</option>
                                @endforeach
                            </select>
                            @error('user_id') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">First Name</label>
                            <input type="text" wire:model="first_name" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                            @error('first_name') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Last Name</label>
                            <input type="text" wire:model="last_name" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Email</label>
                            <input type="email" wire:model="email" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                            @error('email') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Phone</label>
                            <input type="text" wire:model="phone" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Source</label>
                            <select wire:model="source" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                                <option value="">Select source…</option>
                                @foreach ($sourceOptions as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Hotness</label>
                            <select wire:model="hotness" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                                <option value="">Not set</option>
                                @foreach ($hotnessOptions as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Next Follow-up</label>
                            <input type="datetime-local" wire:model="next_follow_up" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Notes</label>
                            <textarea wire:model="notes" rows="3" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100"></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-5">
                        <button type="button" wire:click="closeModal" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                            Cancel
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="save" class="rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-teal-700 disabled:opacity-60">
                            <span wire:loading.remove wire:target="save">Add Prospect</span>
                            <span wire:loading wire:target="save">Saving…</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Move-to-stage Modal --}}
    @if ($movingProspectId && $movingToStage && $movingProspect)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4" wire:click.self="cancelMove">
            <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl sm:p-8">
                <div class="flex items-start justify-between">
                    <h2 class="text-xl font-extrabold text-teal-800">{{ trim($movingProspect->first_name.' '.$movingProspect->last_name) ?: 'Unnamed' }} &rarr; {{ $stageOptions[$movingToStage]['label'] }}</h2>
                    <button type="button" wire:click="cancelMove" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form wire:submit.prevent="confirmMove" class="mt-6 space-y-4">
                    @if (count($stageOptions[$movingToStage]['actions']) > 1)
                        <div>
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Activity Type</label>
                            <select wire:model="moveAction" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                                @foreach ($stageOptions[$movingToStage]['actions'] as $action)
                                    <option value="{{ $action }}">{{ $action }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div>
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Date &amp; Time</label>
                        <input type="datetime-local" wire:model="moveDate" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                        @error('moveDate') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Notes</label>
                        <textarea wire:model="moveNotes" rows="2" placeholder="What happened?" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100"></textarea>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-500">Next Follow-up (optional)</label>
                        <input type="datetime-local" wire:model="moveNextFollowUp" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-800 outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100">
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-5">
                        <button type="button" wire:click="cancelMove" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                            Cancel
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="confirmMove" class="rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-teal-700 disabled:opacity-60">
                            <span wire:loading.remove wire:target="confirmMove">Confirm Move</span>
                            <span wire:loading wire:target="confirmMove">Saving…</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Remove Confirm Modal --}}
    @if ($removingId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4" wire:click.self="cancelRemove">
            <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl">
                <h3 class="text-lg font-bold text-teal-800">Remove this prospect from the pipeline?</h3>
                <p class="mt-2 text-sm text-slate-500">This permanently deletes their record and activity history. This action can't be undone.</p>
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" wire:click="cancelRemove" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="button" wire:click="remove" class="rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-rose-500">
                        Remove
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Prospect Details / Activity History Popup --}}
    @if ($viewingProspectId && $viewingProspect)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4" wire:click.self="closeDetails">
            <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl sm:p-8">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-teal-600">Prospect Details</p>
                        <h2 class="mt-1 text-xl font-extrabold text-teal-800">{{ trim($viewingProspect->first_name.' '.$viewingProspect->last_name) ?: 'Unnamed' }}</h2>
                    </div>
                    <button type="button" wire:click="closeDetails" class="rounded-full p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="mt-5 grid gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm sm:grid-cols-2">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Owner</p>
                        <p class="text-slate-700">{{ $viewingProspect->user?->username ?? 'Unassigned' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Email</p>
                        <p class="text-slate-700">{{ $viewingProspect->email ?: '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Phone</p>
                        <p class="text-slate-700">{{ $viewingProspect->phone ?: '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Source</p>
                        <p class="text-slate-700">{{ $viewingProspect->source ?: '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Hotness</p>
                        <p class="text-slate-700">{{ $viewingProspect->hotness ?: '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Next Follow-up</p>
                        <p class="text-slate-700">{{ $viewingProspect->next_follow_up ? $viewingProspect->next_follow_up->format('M d, Y g:ia') : '—' }}</p>
                    </div>
                    @if ($viewingProspect->notes)
                        <div class="sm:col-span-2">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Notes</p>
                            <p class="text-slate-700">{{ $viewingProspect->notes }}</p>
                        </div>
                    @endif
                </div>

                <div class="mt-6">
                    <p class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-500">Activity History ({{ $viewingActivities->count() }})</p>

                    @if ($viewingActivities->isEmpty())
                        <div class="rounded-xl border border-dashed border-slate-200 p-8 text-center">
                            <p class="text-sm font-semibold text-slate-600">No activity logged yet</p>
                        </div>
                    @else
                        <ul class="space-y-3">
                            @foreach ($viewingActivities as $activity)
                                @php $option = $activityOptions[$activity->action] ?? null; @endphp
                                <li wire:key="view-activity-{{ $activity->id }}" class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white p-4">
                                    <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ \App\Models\Timeline::actionBadgeClass($activity->action) }}">
                                        <x-dashboard-icon :name="$option['icon'] ?? 'clock'" class="h-4.5 w-4.5" />
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold {{ \App\Models\Timeline::actionBadgeClass($activity->action) }}">{{ $option['label'] ?? $activity->action }}</span>
                                            <span class="text-xs text-slate-500">{{ $activity->action_at?->format('M d, Y g:ia') ?? '—' }}</span>
                                        </div>
                                        @if ($activity->notes)
                                            <p class="mt-2 text-sm text-slate-600">{{ $activity->notes }}</p>
                                        @endif
                                        @if ($activity->next_follow_up_dt)
                                            <p class="mt-2 inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-semibold text-amber-700">
                                                Next follow-up: {{ $activity->next_follow_up_dt->format('M d, Y g:ia') }}
                                            </p>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="mt-6 flex items-center justify-end gap-3 border-t border-slate-200 pt-5">
                    <button type="button" wire:click="closeDetails" class="rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-teal-700">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
