<div class="flex-1 overflow-y-auto px-4 py-6 scrollbar-thin scrollbar-thumb-slate-300 scrollbar-track-transparent">
    @foreach (\App\Support\AdminSidebarMenu::groups() as $group)
        <div class="mb-8">
            <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">{{ $group['label'] }}</p>
            <nav class="space-y-1">
                @foreach ($group['items'] as $item)
                    @php
                        $children = $item['children'] ?? [];
                        $href = \App\Support\Nav::route($item['route'] ?? null);
                        $isCurrent = request()->routeIs($item['route']);
                        $hasActiveChild = collect($children)->contains(fn ($child) => request()->routeIs($child['route']));
                    @endphp

                    @if ($children)
                        <div class="rounded-2xl" x-data="{ open: {{ $isCurrent || $hasActiveChild ? 'true' : 'false' }} }">
                            <div class="flex items-center">
                                <a href="{{ $href }}" class="group flex min-w-0 flex-1 items-center gap-3 rounded-2xl px-4 py-3 font-medium transition {{ $isCurrent || $hasActiveChild ? 'bg-white text-slate-900' : 'text-slate-200 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-300 hover:text-indigo-700' }}">
                                    <x-dashboard-icon :name="$item['icon']" class="h-5 w-5 {{ $isCurrent || $hasActiveChild ? 'text-teal-700' : 'text-slate-400 group-hover:text-slate-700' }}" />
                                    <span>{{ $item['label'] }}</span>
                                </a>
                                <button type="button" class="mr-2 inline-flex h-8 w-8 items-center justify-center rounded-xl text-slate-300 hover:bg-white/10 hover:text-white" @click.stop="open = ! open" aria-label="Toggle {{ $item['label'] }} submenu">
                                    <svg class="h-4 w-4 transition" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                            </div>
                            <div class="ml-11 mt-1 space-y-1" x-show="open" x-transition>
                                @foreach ($children as $child)
                                    <a href="{{ \App\Support\Nav::route($child['route'] ?? null) }}" class="block rounded-xl px-3 py-2 text-sm font-medium transition {{ request()->routeIs($child['route']) ? 'bg-white text-slate-900' : 'text-slate-200 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-300 hover:text-indigo-700' }}">
                                        {{ $child['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $href }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 font-medium transition {{ $isCurrent ? 'bg-white text-slate-900' : 'text-slate-200 hover:bg-gradient-to-r hover:from-indigo-100 hover:to-blue-300 hover:text-indigo-700' }}">
                            <x-dashboard-icon :name="$item['icon']" class="h-5 w-5 {{ $isCurrent ? 'text-teal-700' : 'text-slate-400 group-hover:text-slate-700' }}" />
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endif
                @endforeach
            </nav>
        </div>
    @endforeach
</div>
