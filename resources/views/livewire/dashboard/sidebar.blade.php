<div class="flex h-full flex-col" x-data="memberSidebar()" x-on:livewire:navigated.window="sync()">
    <div class="relative border-b border-white/10 px-6 pb-5 pt-6">
        @if ($mobile)
            <button type="button" class="absolute right-5 top-5 z-10 inline-flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500 text-white shadow-lg shadow-emerald-950/40 ring-2 ring-white transition hover:bg-emerald-400" @click="$dispatch('close-mobile-sidebar')">
                <span class="sr-only">Close navigation</span>
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18" /></svg>
            </button>
        @endif

        <x-spa-link :href="route('landing.how')" :spa="false" class="flex flex-col items-center gap-1 rounded-2xl bg-slate-50 px-2 py-2 shadow-sm text-center">
            <x-application-logo class="h-16 w-auto max-w-full shrink-0 object-contain" />
            <p class="text-xs font-semibold uppercase tracking-[0.32em] text-emerald-600/80">{{ $brandEyebrow }}</p>
        </x-spa-link>
    </div>

    <div class="flex-1 overflow-y-auto px-4 space-y-3 py-4" wire:navigate:scroll>
        @foreach ($menuGroups as $group)
            <div>
                <p class="px-3 text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400">{{ $group['label'] }}</p>
                <div class="mt-1.5 space-y-0.5">
                    @foreach ($group['items'] as $item)
                        @php
                            $children = $item['children'] ?? [];
                            $href = \App\Support\Nav::route($item['route'] ?? null, $item['params'] ?? []);
                            $itemPath = $href !== '#' ? parse_url($href, PHP_URL_PATH) : '';
                            $groupKey = ($group['label'].'-'.$item['label']);
                            $childItems = collect($children)->map(function ($child) {
                                $childHref = \App\Support\Nav::route($child['route'] ?? null, $child['params'] ?? []);

                                return [
                                    'label' => $child['label'],
                                    'href' => $childHref,
                                    'path' => $childHref !== '#' ? parse_url($childHref, PHP_URL_PATH) : '',
                                ];
                            });
                            $childPaths = $childItems->pluck('path')->filter()->values();

                            $currentPath = '/'.ltrim(request()->path(), '/');
                            $groupIsActive = $itemPath !== '' && $currentPath === $itemPath;
                            if (! $groupIsActive) {
                                $groupIsActive = $childPaths->contains(function ($childPath) use ($currentPath) {
                                    return $childPath && ($currentPath === $childPath || str_starts_with($currentPath, $childPath.'/'));
                                });
                            }
                        @endphp

                        <div class="rounded-3xl border border-white/5 bg-white/0 transition hover:bg-white/5">
                            <div class="flex items-center">
                                <x-spa-link
                                    :href="$href"
                                    :spa="$item['spa'] ?? true"
                                    class="group flex min-w-0 flex-1 items-center gap-3 rounded-3xl px-3 py-1.5 text-sm font-medium transition"
                                        x-bind:class="isActive({{ \Illuminate\Support\Js::from($itemPath) }}, {{ \Illuminate\Support\Js::from($childPaths) }}) ? 'bg-emerald-500 text-slate-950 shadow-lg shadow-emerald-950/20' : 'text-slate-200 hover:text-emerald-300'"
                                >
                                    <span
                                        class="inline-flex h-7 w-7 items-center justify-center rounded-2xl"
                                        x-bind:class="isActive({{ \Illuminate\Support\Js::from($itemPath) }}, {{ \Illuminate\Support\Js::from($childPaths) }}) ? 'bg-slate-950/10 text-slate-950' : 'bg-white/5 text-emerald-300 group-hover:bg-white/10'"
                                    >
                                        <x-dashboard-icon :name="$item['icon']" class="h-4 w-4" />
                                    </span>
                                    <span>{{ $item['label'] }}</span>
                                </x-spa-link>

                                @if ($children)
                                    <button type="button" class="mr-3 inline-flex h-6 w-6 items-center justify-center rounded-xl text-slate-300 transition hover:bg-white/10 hover:text-white" onclick="event.stopPropagation(); const w = this.closest('.rounded-3xl'); w.querySelector('.wla-nav-submenu')?.classList.toggle('is-open'); this.querySelector('.wla-chevron')?.classList.toggle('rotate-180');" aria-label="Toggle {{ $item['label'] }} submenu">
                                        <svg class="wla-chevron h-4 w-4 transition {{ $groupIsActive ? 'rotate-180' : '' }}" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" /></svg>
                                    </button>
                                @endif
                            </div>

                            @if ($children)
                                <div class="wla-nav-submenu space-y-0.5 px-3 pb-1.5 {{ $groupIsActive ? 'is-open' : '' }}">
                                    @foreach ($childItems as $child)
                                        <x-spa-link
                                            :href="$child['href']"
                                            class="flex items-center rounded-2xl px-4 py-1.5 text-sm transition"
                                            x-bind:class="path === {{ \Illuminate\Support\Js::from($child['path']) }} ? 'bg-emerald-500/15 text-emerald-200' : 'text-slate-300 hover:bg-white/5 hover:text-emerald-300'"
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
                <p class="px-3 text-[11px] font-semibold uppercase tracking-[0.28em] text-yellow-300/80">{{ $switchLabel }}</p>
                <div class="mt-1.5 space-y-0.5">
                    @foreach ($switchItems as $item)
                        <x-spa-link :href="\App\Support\Nav::route($item['route'] ?? null)" :spa="$item['spa'] ?? true" class="flex items-center gap-3 rounded-3xl px-3 py-1.5 text-sm font-medium text-slate-200 transition hover:bg-white/5 hover:text-emerald-300">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-2xl bg-yellow-400/10 text-yellow-300">
                                <x-dashboard-icon :name="$item['icon']" class="h-4 w-4" />
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
