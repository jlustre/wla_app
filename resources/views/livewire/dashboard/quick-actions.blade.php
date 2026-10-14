<div class="rounded-[20px] border border-slate-200/80 wla-hero p-4 text-white shadow-[0_28px_90px_-55px_rgba(15,23,42,0.9)]">
    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-300">Quick Actions</p>
    <h2 class="mt-2 text-xl font-bold">Keep member momentum moving</h2>
    <p class="mt-1 max-w-2xl text-sm text-slate-300">Jump directly into the sponsor tasks that affect onboarding, education, and engagement this week.</p>

    <div class="mt-4 grid gap-2 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($actions as $action)
            <x-spa-link :href="\Illuminate\Support\Facades\Route::has($action['route']) ? route($action['route']) : '#'" :spa="($action['route'] ?? '') !== 'member.genealogy'" class="group rounded-2xl border border-white/10 bg-white/5 p-3 transition hover:border-emerald-400/40 hover:bg-white/10">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-emerald-500/15 text-emerald-200 transition group-hover:bg-emerald-500/25">
                    <x-dashboard-icon :name="$action['icon']" class="h-5 w-5" />
                </span>
                <p class="mt-2 text-sm font-semibold text-white">{{ $action['label'] }}</p>
            </x-spa-link>
        @endforeach
    </div>
</div>