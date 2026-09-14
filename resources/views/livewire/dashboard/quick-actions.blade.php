<div class="rounded-[32px] border border-slate-200/80 bg-[linear-gradient(135deg,rgba(11,23,48,0.98),rgba(17,31,61,0.95))] p-6 text-white shadow-[0_28px_90px_-55px_rgba(15,23,42,0.9)]">
    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-blue-200">Quick Actions</p>
    <h2 class="mt-3 text-2xl font-bold">Keep member momentum moving</h2>
    <p class="mt-2 max-w-2xl text-sm text-slate-300">Jump directly into the sponsor tasks that affect onboarding, education, and engagement this week.</p>

    <div class="mt-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($actions as $action)
            <x-spa-link :href="\Illuminate\Support\Facades\Route::has($action['route']) ? route($action['route']) : '#'" :spa="($action['route'] ?? '') !== 'member.genealogy'" class="group rounded-3xl border border-white/10 bg-white/5 p-4 transition hover:border-blue-400/40 hover:bg-white/10">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-500/15 text-blue-200 transition group-hover:bg-blue-500/25">
                    <x-dashboard-icon :name="$action['icon']" class="h-5 w-5" />
                </span>
                <p class="mt-4 text-sm font-semibold text-white">{{ $action['label'] }}</p>
            </x-spa-link>
        @endforeach
    </div>
</div>