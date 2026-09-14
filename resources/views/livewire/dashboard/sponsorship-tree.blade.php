<div class="space-y-6">
    <div class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_auto] xl:items-end">
        <div class="grid gap-4 md:grid-cols-2 2xl:grid-cols-4">
            <div class="rounded-[28px] border border-white/70 bg-white/90 p-5 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Visible Levels</p>
                <p class="mt-3 text-3xl font-bold text-slate-950 dark:text-white">{{ $stats['visible_levels'] }}</p>
            </div>
            <div class="rounded-[28px] border border-white/70 bg-white/90 p-5 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Visible Members</p>
                <p class="mt-3 text-3xl font-bold text-slate-950 dark:text-white">{{ $stats['visible_members'] }}</p>
            </div>
            <div class="rounded-[28px] border border-white/70 bg-white/90 p-5 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Active Members</p>
                <p class="mt-3 text-3xl font-bold text-emerald-600 dark:text-emerald-300">{{ $stats['active_members'] }}</p>
            </div>
            <div class="rounded-[28px] border border-white/70 bg-white/90 p-5 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.55)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Focused Branch</p>
                <p class="mt-3 text-3xl font-bold text-slate-950 dark:text-white">{{ $stats['focused_descendants'] }}</p>
            </div>
        </div>

        <div class="rounded-[28px] border border-amber-200/70 bg-amber-50/80 px-5 py-4 text-sm text-amber-900 shadow-[0_18px_60px_-42px_rgba(180,83,9,0.35)] dark:border-amber-400/20 dark:bg-amber-400/10 dark:text-amber-100">
            Click a member to pivot the explorer into that branch instead of rendering the whole tree at once.
        </div>
    </div>

    @if ($tree)
        <div class="rounded-[34px] border border-white/70 bg-[linear-gradient(180deg,rgba(255,255,255,0.94),rgba(248,250,252,0.92))] p-6 shadow-[0_30px_100px_-55px_rgba(15,23,42,0.85)] backdrop-blur dark:border-slate-800 dark:bg-[linear-gradient(180deg,rgba(15,23,42,0.96),rgba(15,23,42,0.9))]">
            <div class="grid gap-6 xl:grid-cols-[20rem_minmax(0,1fr)]">
                <aside class="space-y-5 rounded-[30px] border border-slate-200/80 bg-white/85 p-5 shadow-[0_24px_80px_-50px_rgba(15,23,42,0.35)] dark:border-slate-800 dark:bg-slate-950/70">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Explorer Controls</p>
                        <div class="mt-4 space-y-3">
                            <div>
                                <label for="genealogy-search" class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Search Members</label>
                                <div class="mt-2 flex gap-2">
                                    <input id="genealogy-search" type="text" wire:model.live.debounce.250ms="search" placeholder="Username or email" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm outline-none transition focus:border-blue-400 focus:ring-4 focus:ring-blue-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:ring-blue-500/20" />
                                    @if ($search !== '')
                                        <button type="button" wire:click="clearSearch" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Clear</button>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Visible Depth</p>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach ([2, 3, 4, 5] as $depth)
                                        <button type="button" wire:click="$set('maxDepth', {{ $depth }})" class="rounded-full px-3 py-2 text-sm font-semibold transition {{ $maxDepth === $depth ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700' }}">
                                            {{ $depth + 1 }} levels
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <button type="button" wire:click="$toggle('activeOnly')" class="flex w-full items-center justify-between rounded-2xl border px-4 py-3 text-sm font-semibold transition {{ $activeOnly ? 'border-emerald-300 bg-emerald-50 text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-200' : 'border-slate-200 text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                                <span>Show active only</span>
                                <span>{{ $activeOnly ? 'On' : 'Off' }}</span>
                            </button>

                            <div class="flex gap-2">
                                <button type="button" wire:click="focusParent" class="flex-1 rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 disabled:opacity-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800" @disabled(($selectedNode['parent_id'] ?? null) === null)>
                                    Up one level
                                </button>
                                <button type="button" wire:click="jumpToRoot" class="flex-1 rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950">
                                    Root focus
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[28px] bg-[linear-gradient(135deg,rgba(11,23,48,0.98),rgba(17,31,61,0.94)_65%,rgba(47,111,237,0.86))] p-5 text-white shadow-[0_24px_80px_-48px_rgba(15,23,42,0.6)]">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-blue-100/80">Focused Member</p>
                        <div class="mt-4 flex items-start gap-4">
                            @if (! empty($selectedNode['avatar']))
                                <img src="{{ $selectedNode['avatar'] }}" alt="{{ $selectedNode['name'] }} avatar" class="h-14 w-14 rounded-2xl object-cover ring-2 ring-white/20" />
                            @else
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/12 text-lg font-bold text-white">{{ $selectedNode['initials'] ?? '--' }}</div>
                            @endif
                            <div class="min-w-0">
                                <p class="truncate text-xl font-bold">{{ $selectedNode['name'] ?? 'No member selected' }}</p>
                                <p class="truncate text-sm text-slate-200">{{ $selectedNode['email'] ?? 'Select a node to inspect' }}</p>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="rounded-full bg-white/12 px-3 py-1 text-xs font-semibold">{{ $selectedNode['status_label'] ?? 'Unknown' }}</span>
                            <span class="rounded-full bg-white/12 px-3 py-1 text-xs font-semibold">Level {{ $selectedNode['level'] ?? 0 }}</span>
                        </div>
                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-2xl bg-white/10 p-4">
                                <p class="text-xs uppercase tracking-[0.24em] text-blue-100/70">Direct members</p>
                                <p class="mt-2 text-2xl font-bold">{{ $selectedNode['direct_count'] ?? 0 }}</p>
                            </div>
                            <div class="rounded-2xl bg-white/10 p-4">
                                <p class="text-xs uppercase tracking-[0.24em] text-blue-100/70">Branch size</p>
                                <p class="mt-2 text-2xl font-bold">{{ $selectedNode['descendant_count'] ?? 0 }}</p>
                            </div>
                        </div>
                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-2xl bg-white/10 p-4">
                                <p class="text-xs uppercase tracking-[0.24em] text-blue-100/70">City</p>
                                <p class="mt-2 text-sm font-semibold text-white">{{ $selectedNode['city'] ?? 'Not shared' }}</p>
                            </div>
                            <div class="rounded-2xl bg-white/10 p-4">
                                <p class="text-xs uppercase tracking-[0.24em] text-blue-100/70">Phone</p>
                                <p class="mt-2 text-sm font-semibold text-white">{{ $selectedNode['phone'] ?? 'Not shared' }}</p>
                            </div>
                        </div>
                        <p class="mt-4 text-sm text-slate-200">Joined {{ $selectedNode['joined'] ?? 'Recently' }}</p>
                        <p class="mt-3 text-sm leading-6 text-slate-200/90">{{ ($selectedNode['bio'] ?? null) ?: 'No member bio has been added yet for this profile.' }}</p>
                    </div>

                    @if ($searchResults)
                        <div class="rounded-[28px] border border-slate-200/80 bg-white/85 p-5 dark:border-slate-800 dark:bg-slate-900/80">
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Search Results</p>
                            <div class="mt-4 space-y-2">
                                @foreach ($searchResults as $result)
                                    <button type="button" wire:click="focusNode({{ $result['id'] }})" class="flex w-full items-start justify-between rounded-2xl bg-slate-50 px-4 py-3 text-left transition hover:bg-slate-100 dark:bg-slate-800/70 dark:hover:bg-slate-800">
                                        <span class="min-w-0">
                                            <span class="block truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $result['name'] }}</span>
                                            <span class="block truncate text-sm text-slate-500 dark:text-slate-400">{{ $result['email'] }}</span>
                                        </span>
                                        <span class="ml-3 rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-500/10 dark:text-blue-300">L{{ $result['level'] }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </aside>

                <div class="min-w-0 space-y-5 rounded-[30px] border border-slate-200/80 bg-white/75 p-5 shadow-[0_24px_80px_-50px_rgba(15,23,42,0.3)] dark:border-slate-800 dark:bg-slate-950/65">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Focused Path</p>
                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                @foreach ($lineage as $node)
                                    <button type="button" wire:click="focusNode({{ $node['id'] }})" class="inline-flex items-center gap-2 rounded-full px-3 py-2 text-sm font-semibold transition {{ ($selectedNode['id'] ?? null) === $node['id'] ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700' }}">
                                        <span>{{ $node['name'] }}</span>
                                        <span class="text-xs {{ ($selectedNode['id'] ?? null) === $node['id'] ? 'text-white/70 dark:text-slate-500' : 'text-slate-400' }}>L{{ $node['level'] }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="text-sm text-slate-500 dark:text-slate-400">Only one branch stays expanded at a time, which keeps the explorer readable on dense networks.</p>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">Root: {{ $stats['root_name'] }}</span>
                        </div>
                    </div>

                    <div
                        x-data="{
                            scale: 1,
                            panX: 0,
                            panY: 0,
                            isPanning: false,
                            startX: 0,
                            startY: 0,
                            startPanX: 0,
                            startPanY: 0,
                            zoomIn() { this.scale = Math.min(1.8, +(this.scale + 0.1).toFixed(2)); },
                            zoomOut() { this.scale = Math.max(0.7, +(this.scale - 0.1).toFixed(2)); },
                            resetView() { this.scale = 1; this.panX = 0; this.panY = 0; },
                            startPan(event) {
                                if (event.target.closest('button, a, input, select, textarea')) return;
                                this.isPanning = true;
                                this.startX = event.clientX;
                                this.startY = event.clientY;
                                this.startPanX = this.panX;
                                this.startPanY = this.panY;
                            },
                            pan(event) {
                                if (! this.isPanning) return;
                                this.panX = this.startPanX + (event.clientX - this.startX);
                                this.panY = this.startPanY + (event.clientY - this.startY);
                            },
                            endPan() {
                                this.isPanning = false;
                            },
                            wheel(event) {
                                event.preventDefault();
                                if (event.deltaY > 0) {
                                    this.zoomOut();
                                    return;
                                }
                                this.zoomIn();
                            },
                        }"
                        class="space-y-4"
                    >
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="flex flex-wrap items-center gap-2">
                                <button type="button" @click="zoomOut()" class="rounded-2xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">- Zoom</button>
                                <button type="button" @click="zoomIn()" class="rounded-2xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">+ Zoom</button>
                                <button type="button" @click="resetView()" class="rounded-2xl bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950">Reset view</button>
                            </div>
                            <div class="rounded-full bg-slate-100 px-3 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 dark:bg-slate-800 dark:text-slate-300">
                                Scale <span x-text="scale.toFixed(2) + 'x'"></span>
                            </div>
                        </div>

                        <div class="rounded-[28px] border border-slate-200/80 bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.08),_transparent_28%),linear-gradient(180deg,rgba(248,250,252,0.92),rgba(255,255,255,0.88))] p-4 dark:border-slate-800 dark:bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.12),_transparent_24%),linear-gradient(180deg,rgba(15,23,42,0.9),rgba(15,23,42,0.82))]">
                            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Mini Map</p>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Column intensity reflects how many members are currently visible per branch.</p>
                                </div>
                                <button type="button" @click="resetView()" class="rounded-full bg-white px-3 py-2 text-xs font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">Center canvas</button>
                            </div>
                            <div class="grid gap-2 {{ count($miniMapNodes) > 0 ? 'grid-cols-'.count($miniMapNodes) : '' }}" style="grid-template-columns: repeat({{ max(count($miniMapNodes), 1) }}, minmax(0, 1fr));">
                                @forelse ($miniMapNodes as $miniMapNode)
                                    <button type="button" wire:click="focusNode({{ $miniMapNode['parent_id'] }})" class="rounded-2xl border border-slate-200/80 bg-white/80 p-3 text-left shadow-sm transition hover:bg-white dark:border-slate-700 dark:bg-slate-900/80 dark:hover:bg-slate-900">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="truncate text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">{{ $miniMapNode['parent_name'] }}</span>
                                            <span class="text-xs font-semibold text-blue-600 dark:text-blue-300">{{ $miniMapNode['count'] }}</span>
                                        </div>
                                        <div class="mt-3 h-2 rounded-full bg-slate-200 dark:bg-slate-700">
                                            <div class="h-2 rounded-full bg-blue-500" style="width: {{ min(max($miniMapNode['count'] * 14, 18), 100) }}%"></div>
                                        </div>
                                    </button>
                                @empty
                                    <div class="rounded-2xl border border-dashed border-slate-200 px-4 py-6 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                                        No mini-map data yet.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="overflow-hidden rounded-[28px] border border-slate-200/80 bg-white/70 dark:border-slate-800 dark:bg-slate-950/55">
                            <div
                                class="overflow-auto pb-2"
                                @wheel="wheel($event)"
                                @mousedown="startPan($event)"
                                @mousemove.window="pan($event)"
                                @mouseup.window="endPan()"
                                @mouseleave.window="endPan()"
                            >
                                <div class="min-w-max origin-top-left p-4 transition-transform duration-150" :style="`transform: translate(${panX}px, ${panY}px) scale(${scale});`">
                                    <div class="flex min-w-max gap-4">
                                        @forelse ($columns as $column)
                                            <section class="w-[19rem] shrink-0 rounded-[28px] border border-slate-200/80 bg-slate-50/80 p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900/70">
                                                <div class="border-b border-slate-200/80 pb-3 dark:border-slate-800">
                                                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Children of</p>
                                                    <div class="mt-2 flex items-center justify-between gap-3">
                                                        <p class="truncate text-lg font-bold text-slate-950 dark:text-white">{{ $column['parent']['name'] }}</p>
                                                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ count($column['nodes']) }}/{{ $column['parent']['direct_count'] }}</span>
                                                    </div>
                                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ count($column['nodes']) }} visible direct members</p>
                                                </div>

                                                <div class="mt-4 space-y-3">
                                                    @forelse ($column['nodes'] as $node)
                                                        <button type="button" wire:click="focusNode({{ $node['id'] }})" class="w-full rounded-[24px] border px-4 py-4 text-left transition {{ $column['selected_child_id'] === $node['id'] || ($selectedNode['id'] ?? null) === $node['id'] ? 'border-blue-300 bg-white shadow-[0_18px_50px_-36px_rgba(59,130,246,0.7)] dark:border-blue-500/40 dark:bg-slate-950' : 'border-transparent bg-white/90 hover:border-slate-300 hover:bg-white dark:bg-slate-900/80 dark:hover:border-slate-700' }}">
                                                            <div class="flex items-start justify-between gap-3">
                                                                <div class="flex min-w-0 gap-3">
                                                                    @if (! empty($node['avatar']))
                                                                        <img src="{{ $node['avatar'] }}" alt="{{ $node['name'] }} avatar" class="h-11 w-11 rounded-2xl object-cover ring-1 ring-slate-200 dark:ring-slate-700" />
                                                                    @else
                                                                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-sm font-bold text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $node['initials'] }}</div>
                                                                    @endif
                                                                    <div class="min-w-0">
                                                                        <p class="truncate text-base font-bold text-slate-950 dark:text-white">{{ $node['name'] }}</p>
                                                                        <p class="truncate text-sm text-slate-500 dark:text-slate-400">{{ $node['email'] }}</p>
                                                                    </div>
                                                                </div>
                                                                <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $node['status'] === 'active' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300' }}">{{ $node['status_label'] }}</span>
                                                            </div>
                                                            <div class="mt-4 flex flex-wrap gap-2">
                                                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $node['direct_count'] }} direct</span>
                                                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $node['descendant_count'] }} branch</span>
                                                                <span class="rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-semibold text-blue-700 dark:bg-blue-500/10 dark:text-blue-300">L{{ $node['level'] }}</span>
                                                            </div>
                                                            @if (! empty($node['city']) || ! empty($node['phone']))
                                                                <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">
                                                                    {{ $node['city'] ?: 'City hidden' }}
                                                                    @if (! empty($node['phone']))
                                                                        • {{ $node['phone'] }}
                                                                    @endif
                                                                </p>
                                                            @endif
                                                            @if ($node['has_more_children'])
                                                                <p class="mt-3 text-xs font-semibold uppercase tracking-[0.24em] text-amber-600 dark:text-amber-300">Showing first 8 direct members. Focus this card to lazy-load the next branch.</p>
                                                            @endif
                                                        </button>
                                                    @empty
                                                        <div class="rounded-[24px] border border-dashed border-slate-200 px-4 py-10 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                                                            No members are visible in this branch for the current filters.
                                                        </div>
                                                    @endforelse
                                                </div>
                                            </section>
                                        @empty
                                            <div class="rounded-[28px] border border-dashed border-slate-200 px-6 py-16 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                                                No visible branch to display. Adjust the depth or active filter to explore more members.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="rounded-3xl border border-dashed border-slate-200 bg-white/80 px-6 py-16 text-center dark:border-slate-700 dark:bg-slate-900/70">
            <p class="text-lg font-semibold text-slate-900 dark:text-white">No sponsor tree available</p>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Once direct sponsored members are present, the explorer will render here without crowding the screen.</p>
        </div>
    @endif
</div>