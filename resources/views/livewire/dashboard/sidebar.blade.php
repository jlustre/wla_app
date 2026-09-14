<div class="flex h-full flex-col" x-data="memberSidebar()" x-on:livewire:navigated.window="sync()">
    <div class="border-b border-white/10 px-6 pb-5 pt-6">
        <div class="flex items-center gap-4">
            <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-gradient-to-br from-blue-500 via-blue-400 to-amber-400 text-lg font-extrabold text-slate-950 shadow-lg shadow-blue-950/20">
                W
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.32em] text-blue-200/75">{{ $brandEyebrow }}</p>
                <h1 class="mt-1 text-lg font-bold text-white">Wealth Legacy Alliance</h1>
                <p class="mt-1 text-sm text-slate-300">{{ $brandSubtitle }}</p>
            </div>
        </div>

        <div class="mt-5 rounded-3xl border border-white/10 bg-white/5 p-4 backdrop-blur">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-semibold text-white">{{ $user?->username ?? 'Member' }}</p>
                    <p class="text-xs text-slate-300">{{ $user?->email ?? 'member@wealthlegacyalliance.test' }}</p>
                </div>
                <livewire:dashboard.status-badge :label="\Illuminate\Support\Str::headline((string) ($user?->status?->value ?? $user?->status ?? 'active'))" tone="gold" />
            </div>
        </div>
    </div>

    <div class="flex-1 space-y-6 overflow-y-auto px-4 py-6" wire:navigate:scroll>
        @foreach ($menuGroups as $group)
            <div>
                <p class="px-3 text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400">{{ $group['label'] }}</p>
                <div class="mt-3 space-y-2">
                    @foreach ($group['items'] as $item)
                        @php
                            $children = $item['children'] ?? [];
                            $href = \App\Support\Nav::route($item['route'] ?? null);
                            $itemPath = $href !== '#' ? parse_url($href, PHP_URL_PATH) : '';
                            $groupKey = ($group['label'].'-'.$item['label']);
                            $childItems = collect($children)->map(function ($child) {
                                $childHref = \App\Support\Nav::route($child['route'] ?? null);

                                return [
                                    'label' => $child['label'],
                                    'href' => $childHref,
                                    'path' => $childHref !== '#' ? parse_url($childHref, PHP_URL_PATH) : '',
                                ];
                            });
                            $childPaths = $childItems->pluck('path')->filter()->values();
                        @endphp

                        <div class="rounded-3xl border border-white/5 bg-white/0 transition hover:bg-white/5">
                            <div class="flex items-center">
                                <x-spa-link
                                    :href="$href"
                                    :spa="$item['spa'] ?? true"
                                    class="group flex min-w-0 flex-1 items-center gap-3 rounded-3xl px-3 py-3 text-sm font-medium transition"
                                    x-bind:class="isActive({{ \Illuminate\Support\Js::from($itemPath) }}, {{ \Illuminate\Support\Js::from($childPaths) }}) ? 'bg-white text-slate-950 shadow-lg shadow-slate-950/10' : 'text-slate-200 hover:text-white'"
                                >
                                    <span
                                        class="inline-flex h-10 w-10 items-center justify-center rounded-2xl"
                                        x-bind:class="isActive({{ \Illuminate\Support\Js::from($itemPath) }}, {{ \Illuminate\Support\Js::from($childPaths) }}) ? 'bg-slate-100 text-blue-600' : 'bg-white/5 text-blue-200 group-hover:bg-white/10'"
                                    >
                                        <x-dashboard-icon :name="$item['icon']" class="h-5 w-5" />
                                    </span>
                                    <span>{{ $item['label'] }}</span>
                                </x-spa-link>

                                @if ($children)
                                    <button type="button" class="mr-3 inline-flex h-8 w-8 items-center justify-center rounded-xl text-slate-300 transition hover:bg-white/10 hover:text-white" x-on:click.stop="toggleGroup({{ \Illuminate\Support\Js::from($groupKey) }}, {{ \Illuminate\Support\Js::from($itemPath) }}, {{ \Illuminate\Support\Js::from($childPaths) }})" aria-label="Toggle {{ $item['label'] }} submenu">
                                        <svg class="h-4 w-4 transition" x-bind:class="groupOpen({{ \Illuminate\Support\Js::from($groupKey) }}, {{ \Illuminate\Support\Js::from($itemPath) }}, {{ \Illuminate\Support\Js::from($childPaths) }}) ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" /></svg>
                                    </button>
                                @endif
                            </div>

                            @if ($children)
                                <div x-show="groupOpen({{ \Illuminate\Support\Js::from($groupKey) }}, {{ \Illuminate\Support\Js::from($itemPath) }}, {{ \Illuminate\Support\Js::from($childPaths) }})" x-transition.opacity.duration.200ms class="space-y-1 px-3 pb-3">
                                    @foreach ($childItems as $child)
                                        <x-spa-link
                                            :href="$child['href']"
                                            class="flex items-center rounded-2xl px-4 py-2.5 text-sm transition"
                                            x-bind:class="path === {{ \Illuminate\Support\Js::from($child['path']) }} ? 'bg-blue-500/15 text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white'"
                                        >
                                            {{ $child['label'] }}
                                        </x-spa-link>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        @if ($switchItems)
            <div>
                <p class="px-3 text-[11px] font-semibold uppercase tracking-[0.28em] text-amber-300/80">{{ $switchLabel }}</p>
                <div class="mt-3 space-y-2">
                    @foreach ($switchItems as $item)
                        <x-spa-link :href="\App\Support\Nav::route($item['route'] ?? null)" :spa="$item['spa'] ?? true" class="flex items-center gap-3 rounded-3xl px-3 py-3 text-sm font-medium text-slate-200 transition hover:bg-white/5 hover:text-white">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-400/10 text-amber-300">
                                <x-dashboard-icon :name="$item['icon']" class="h-5 w-5" />
                            </span>
                            <span>{{ $item['label'] }}</span>
                        </x-spa-link>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="border-t border-white/10 px-4 py-4">
        <livewire:dashboard.logout-form />
    </div>
</div>
