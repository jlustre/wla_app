@if (! empty($businessLinkGroups))
<div class="rounded-[24px] border border-slate-200/80 bg-white/90 p-5 shadow-[0_28px_90px_-55px_rgba(15,23,42,0.65)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/85">
    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-600 dark:text-emerald-300">Quick Links</p>
    <h2 class="mt-2 text-xl font-bold text-slate-950 dark:text-white">Jump to anywhere in the business</h2>
    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Every pertinent workspace, tool, and company portal &mdash; one click away.</p>

    <div class="mt-5 space-y-5">
        @foreach ($businessLinkGroups as $group)
            <div>
                <p class="mb-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">{{ $group['label'] }}</p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($group['links'] as $link)
                        <x-spa-link
                            :href="\App\Support\Nav::route($link['route'] ?? null, $link['params'] ?? [])"
                            :spa="$link['spa'] ?? true"
                            class="group inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 py-2 pl-2 pr-3.5 text-sm font-semibold text-slate-700 transition hover:border-emerald-400/60 hover:bg-emerald-50 hover:text-emerald-700 dark:border-slate-700 dark:bg-slate-800/70 dark:text-slate-200 dark:hover:border-emerald-400/40 dark:hover:bg-emerald-500/10 dark:hover:text-emerald-300"
                        >
                            <span class="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-white text-slate-500 transition group-hover:bg-emerald-100 group-hover:text-emerald-700 dark:bg-slate-900 dark:text-slate-400 dark:group-hover:bg-emerald-500/20 dark:group-hover:text-emerald-300">
                                <x-dashboard-icon :name="$link['icon'] ?? 'link'" class="h-3.5 w-3.5" />
                            </span>
                            <span class="whitespace-nowrap">{{ $link['label'] }}</span>
                        </x-spa-link>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif
