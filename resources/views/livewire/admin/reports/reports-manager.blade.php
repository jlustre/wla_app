<div class="w-full space-y-6" wire:key="admin-reports-manager-root">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-600">Admin</p>
            <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-teal-800">Reports and Analytics</h1>
            <p class="mt-1 text-sm text-slate-500">Platform composition and trends. Every section links through to where you can act on it.</p>
        </div>
        <div class="inline-flex rounded-xl bg-white p-1 shadow ring-1 ring-black/5">
            @foreach (['7' => '7d', '14' => '14d', '30' => '30d', '90' => '90d', 'all' => 'All'] as $value => $label)
                <button type="button" wire:click="$set('range', '{{ $value }}')" class="rounded-lg px-3 py-1.5 text-xs font-bold transition {{ $range === $value ? 'bg-teal-600 text-white' : 'text-slate-500 hover:bg-slate-50' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Overview --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <a href="{{ route('admin.users.index') }}" wire:navigate class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5 transition hover:ring-teal-300">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Members</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($overview['members']) }}</p>
        </a>
        <a href="{{ route('admin.prospects.index') }}" wire:navigate class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5 transition hover:ring-teal-300">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Prospects</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($overview['prospects']) }}</p>
        </a>
        <a href="{{ route('admin.companies.index') }}" wire:navigate class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5 transition hover:ring-teal-300">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Companies</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($overview['companies']) }}</p>
        </a>
        <a href="{{ route('admin.sponsor-relationships.index', ['statusFilter' => 'current']) }}" wire:navigate class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5 transition hover:ring-teal-300">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Current Sponsor Links</p>
            <p class="mt-2 text-2xl font-extrabold text-teal-800">{{ number_format($overview['sponsor_current']) }}</p>
        </a>
    </div>

    {{-- Trend chart --}}
    <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5 sm:p-6">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <div>
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500">Growth</h2>
                <p class="text-xs text-slate-400">New members and new prospects per day{{ $range === 'all' ? ' (capped at the last 90 days)' : '' }}.</p>
            </div>
            <div class="flex items-center gap-4 text-xs font-semibold">
                <span class="inline-flex items-center gap-1.5 text-slate-600"><span class="h-2.5 w-2.5 rounded-full bg-teal-600"></span> Members ({{ number_format($trend['totalMembers']) }})</span>
                <span class="inline-flex items-center gap-1.5 text-slate-600"><span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span> Prospects ({{ number_format($trend['totalProspects']) }})</span>
            </div>
        </div>

        <div
            class="relative mt-4"
            x-data="{
                hover: null,
                labels: @js($trend['labels']),
                isoDates: @js($trend['isoDates']),
                members: @js($trend['members']),
                prospects: @js($trend['prospects']),
                width: {{ $trend['width'] }},
                padX: {{ $trend['padX'] }},
                stepX: {{ $trend['stepX'] }},
                move(evt) {
                    const rect = this.$refs.svg.getBoundingClientRect();
                    const x = (evt.clientX - rect.left) * (this.width / rect.width);
                    let idx = this.stepX > 0 ? Math.round((x - this.padX) / this.stepX) : 0;
                    idx = Math.max(0, Math.min(this.labels.length - 1, idx));
                    this.hover = idx;
                },
                leave() { this.hover = null; },
            }"
        >
            <svg
                x-ref="svg"
                viewBox="0 0 {{ $trend['width'] }} {{ $trend['height'] }}"
                class="w-full"
                style="height: 220px"
                @mousemove="move($event)"
                @mouseleave="leave()"
                preserveAspectRatio="none"
            >
                {{-- Gridlines --}}
                @for ($i = 0; $i <= 3; $i++)
                    @php $gy = $trend['padY'] + $i * (($trend['height'] - 2 * $trend['padY']) / 3); @endphp
                    <line x1="{{ $trend['padX'] }}" y1="{{ $gy }}" x2="{{ $trend['width'] - $trend['padX'] }}" y2="{{ $gy }}" stroke="#e1e0d9" stroke-width="1" />
                @endfor

                {{-- Series lines --}}
                <polyline points="{{ $trend['prospectPoints'] }}" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" />
                <polyline points="{{ $trend['memberPoints'] }}" fill="none" stroke="#0d9488" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" />

                {{-- Hover crosshair --}}
                <line
                    x-show="hover !== null"
                    :x1="padX + (hover ?? 0) * stepX"
                    :x2="padX + (hover ?? 0) * stepX"
                    y1="{{ $trend['padY'] }}"
                    y2="{{ $trend['height'] - $trend['padY'] }}"
                    stroke="#c3c2b7"
                    stroke-width="1"
                    stroke-dasharray="3,3"
                />
            </svg>

            {{-- Tooltip --}}
            <div
                x-show="hover !== null"
                x-cloak
                class="pointer-events-none absolute top-0 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs shadow-lg"
                :style="hover !== null ? ('left: ' + Math.min(Math.max((hover / Math.max(labels.length - 1, 1)) * 100, 5), 85) + '%') : ''"
            >
                <p class="font-semibold text-slate-700" x-text="hover !== null ? isoDates[hover] : ''"></p>
                <p class="mt-1 text-teal-700" x-text="hover !== null ? ('Members: ' + members[hover]) : ''"></p>
                <p class="text-amber-600" x-text="hover !== null ? ('Prospects: ' + prospects[hover]) : ''"></p>
            </div>

            {{-- X-axis labels (sampled so they don't crowd) --}}
            <div class="mt-2 flex justify-between text-[10px] text-slate-400">
                @php
                    $labelCount = count($trend['labels']);
                    $showEvery = max(1, (int) ceil($labelCount / 8));
                @endphp
                @foreach ($trend['labels'] as $i => $label)
                    @if ($i % $showEvery === 0 || $i === $labelCount - 1)
                        <span>{{ $label }}</span>
                    @endif
                @endforeach
            </div>
        </div>

        <details class="mt-4 text-xs">
            <summary class="cursor-pointer font-semibold text-slate-500 hover:text-teal-700">View as table</summary>
            <div class="mt-2 max-h-48 overflow-y-auto rounded-xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-100 text-xs">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-3 py-2 text-left font-semibold text-slate-600">Date</th>
                            <th class="px-3 py-2 text-left font-semibold text-slate-600">Members</th>
                            <th class="px-3 py-2 text-left font-semibold text-slate-600">Prospects</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($trend['isoDates'] as $i => $date)
                            <tr>
                                <td class="px-3 py-1.5 text-slate-600">{{ $date }}</td>
                                <td class="px-3 py-1.5 text-slate-600">{{ $trend['members'][$i] }}</td>
                                <td class="px-3 py-1.5 text-slate-600">{{ $trend['prospects'][$i] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </details>
    </div>

    {{-- Prospect funnel + hotness/source --}}
    <div class="grid gap-4 lg:grid-cols-5">
        <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5 lg:col-span-3">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500">Prospect Funnel</h2>
                <a href="{{ route('admin.pipeline') }}" wire:navigate class="text-xs font-semibold text-teal-700 hover:underline">Open Pipeline &rarr;</a>
            </div>
            <div class="mt-4 space-y-2.5">
                @foreach ($funnel as $stage)
                    @php
                        $barColor = match ($stage['color']) {
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
                    <div class="flex items-center gap-3">
                        <div class="w-28 shrink-0 text-right text-xs font-semibold text-slate-600">{{ $stage['label'] }}</div>
                        <div class="relative flex-1">
                            <div class="h-6 w-full overflow-hidden rounded-lg bg-slate-100">
                                <div class="flex h-6 items-center rounded-lg {{ $barColor }} px-2 text-[11px] font-bold text-white transition-all" style="width: {{ max($stage['pct'], $stage['count'] > 0 ? 4 : 0) }}%">
                                    @if ($stage['pct'] > 10) {{ $stage['count'] }} @endif
                                </div>
                            </div>
                            @if ($stage['pct'] <= 10)
                                <span class="absolute inset-y-0 left-2 flex items-center text-[11px] font-bold text-slate-700">{{ $stage['count'] }}</span>
                            @endif
                        </div>
                        <div class="w-12 shrink-0 text-right text-[11px] text-slate-400">{{ $stage['pct'] }}%</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5 lg:col-span-2">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500">Hotness</h2>
                <a href="{{ route('admin.prospects.index') }}" wire:navigate class="text-xs font-semibold text-teal-700 hover:underline">Open Prospects &rarr;</a>
            </div>
            <div class="mt-4 space-y-2.5">
                @foreach ($hotness as $row)
                    @php
                        $barColor = match ($row['color']) {
                            'rose' => 'bg-rose-500',
                            'amber' => 'bg-amber-500',
                            'sky' => 'bg-sky-500',
                            default => 'bg-slate-400',
                        };
                    @endphp
                    <a href="{{ route('admin.prospects.index', in_array($row['label'], ['Hot','Warm','Cold'], true) ? ['hotnessFilter' => $row['label']] : []) }}" wire:navigate class="flex items-center gap-3 rounded-lg px-1 py-0.5 hover:bg-slate-50">
                        <div class="w-16 shrink-0 text-right text-xs font-semibold text-slate-600">{{ $row['label'] }}</div>
                        <div class="relative flex-1">
                            <div class="h-6 w-full overflow-hidden rounded-lg bg-slate-100">
                                <div class="flex h-6 items-center rounded-lg {{ $barColor }} px-2 text-[11px] font-bold text-white" style="width: {{ max($row['pct'], $row['count'] > 0 ? 4 : 0) }}%">
                                    @if ($row['pct'] > 12) {{ $row['count'] }} @endif
                                </div>
                            </div>
                            @if ($row['pct'] <= 12)
                                <span class="absolute inset-y-0 left-2 flex items-center text-[11px] font-bold text-slate-700">{{ $row['count'] }}</span>
                            @endif
                        </div>
                        <div class="w-10 shrink-0 text-right text-[11px] text-slate-400">{{ $row['pct'] }}%</div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Source + Role --}}
    <div class="grid gap-4 lg:grid-cols-2">
        <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500">Lead Source</h2>
                <a href="{{ route('admin.prospects.index') }}" wire:navigate class="text-xs font-semibold text-teal-700 hover:underline">Open Prospects &rarr;</a>
            </div>
            <div class="mt-4 space-y-2.5">
                @foreach ($sources as $row)
                    <a href="{{ route('admin.prospects.index', $row['label'] !== 'Unknown' ? ['sourceFilter' => $row['label']] : []) }}" wire:navigate class="flex items-center gap-3 rounded-lg px-1 py-0.5 hover:bg-slate-50">
                        <div class="w-24 shrink-0 truncate text-right text-xs font-semibold text-slate-600">{{ $row['label'] }}</div>
                        <div class="relative flex-1">
                            <div class="h-5 w-full overflow-hidden rounded-lg bg-slate-100">
                                <div class="h-5 rounded-lg bg-teal-500" style="width: {{ max($row['pct'], $row['count'] > 0 ? 4 : 0) }}%"></div>
                            </div>
                        </div>
                        <div class="w-16 shrink-0 text-right text-[11px] text-slate-400">{{ $row['count'] }} &bull; {{ $row['pct'] }}%</div>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500">Member Roles</h2>
                <a href="{{ route('admin.roles.index') }}" wire:navigate class="text-xs font-semibold text-teal-700 hover:underline">Open Roles &rarr;</a>
            </div>
            <div class="mt-4 space-y-2.5">
                @foreach ($roles as $row)
                    <a href="{{ route('admin.users.index', $row['label'] !== 'No role assigned' ? ['roleFilter' => $row['label']] : []) }}" wire:navigate class="flex items-center gap-3 rounded-lg px-1 py-0.5 hover:bg-slate-50">
                        <div class="w-28 shrink-0 truncate text-right text-xs font-semibold text-slate-600">{{ $row['label'] }}</div>
                        <div class="relative flex-1">
                            <div class="h-5 w-full overflow-hidden rounded-lg bg-slate-100">
                                <div class="h-5 rounded-lg {{ $row['label'] === 'No role assigned' ? 'bg-slate-400' : 'bg-violet-500' }}" style="width: {{ max($row['pct'], $row['count'] > 0 ? 4 : 0) }}%"></div>
                            </div>
                        </div>
                        <div class="w-16 shrink-0 text-right text-[11px] text-slate-400">{{ $row['count'] }} &bull; {{ $row['pct'] }}%</div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Sponsor health + Companies + Audit --}}
    <div class="grid gap-4 lg:grid-cols-3">
        <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500">Sponsor Health</h2>
                <a href="{{ route('admin.sponsor-relationships.index') }}" wire:navigate class="text-xs font-semibold text-teal-700 hover:underline">Open &rarr;</a>
            </div>
            <div class="mt-4 space-y-3 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-slate-600">Current links</span>
                    <span class="font-bold text-teal-800">{{ number_format($sponsor['current']) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-600">Ended (all-time)</span>
                    <span class="font-bold text-teal-800">{{ number_format($sponsor['ended']) }}</span>
                </div>
                <div class="flex items-center justify-between border-t border-slate-100 pt-3">
                    <span class="text-slate-600">Reassigned in range</span>
                    <span class="font-bold text-teal-800">{{ number_format($sponsor['reassigned_in_range']) }}</span>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500">Companies by Category</h2>
                <a href="{{ route('admin.companies.index') }}" wire:navigate class="text-xs font-semibold text-teal-700 hover:underline">Open &rarr;</a>
            </div>
            <ul class="mt-4 space-y-2 text-sm">
                @forelse ($companyCategories as $row)
                    <li class="flex items-center justify-between">
                        <span class="text-slate-600">{{ $row['label'] }}</span>
                        <span class="font-bold text-teal-800">{{ $row['count'] }}</span>
                    </li>
                @empty
                    <li class="text-slate-400">No companies yet.</li>
                @endforelse
            </ul>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500">Audit Activity</h2>
                <a href="{{ route('admin.audit-logs.index') }}" wire:navigate class="text-xs font-semibold text-teal-700 hover:underline">Open &rarr;</a>
            </div>
            <ul class="mt-4 space-y-2 text-sm">
                @forelse ($auditEvents as $row)
                    <li>
                        <a href="{{ route('admin.audit-logs.index', ['eventFilter' => $row['event']]) }}" wire:navigate class="flex items-center justify-between rounded-lg px-1 py-0.5 hover:bg-slate-50">
                            <span class="text-slate-600">{{ $row['label'] }}</span>
                            <span class="font-bold text-teal-800">{{ $row['count'] }}</span>
                        </a>
                    </li>
                @empty
                    <li class="text-slate-400">No audit events in this range.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
