<div class="mt-8 rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl">
  <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-300">Affiliated Companies</p>
      <h2 class="mt-2 text-2xl font-extrabold text-white">Company Management Overview</h2>
    </div>
    <a href="{{ \App\Support\Nav::route('admin.companies.index') }}" class="inline-flex items-center justify-center rounded-xl bg-sky-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-sky-400">
      Manage All Companies
    </a>
  </div>

  <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
    @forelse ($companies as $company)
      @php
        $initials = collect(explode(' ', $company->name))->map(fn ($word) => mb_substr($word, 0, 1))->take(2)->implode('');
        $isActive = (int) $company->status === 1;
      @endphp
      <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-5">
        <div class="flex items-center justify-between">
          <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-500/10 font-bold text-sky-300">{{ strtoupper($initials) }}</div>
          @if ($isActive)
            <span class="rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-semibold text-emerald-300">Active</span>
          @else
            <span class="rounded-full bg-amber-500/10 px-3 py-1 text-xs font-semibold text-amber-300">Inactive</span>
          @endif
        </div>
        <h3 class="mt-4 text-lg font-bold text-white">{{ $company->name }}</h3>
        <p class="mt-1 text-sm text-slate-400">{{ $company->category ?? 'Uncategorized' }}</p>
        <p class="mt-4 text-sm text-slate-300"><span class="font-semibold text-white">{{ number_format($company->joined_members_count) }}</span> joined members</p>
        <div class="mt-5 grid grid-cols-2 gap-2 text-xs font-semibold">
          <a href="{{ \App\Support\Nav::localHref($company->website_link) }}" target="{{ $company->website_link ? '_blank' : '_self' }}" rel="noreferrer" class="rounded-lg border border-white/10 px-3 py-2 text-center text-slate-200 hover:bg-white/10">Website</a>
          <a href="{{ \App\Support\Nav::route('admin.companies.show', $company) }}" class="rounded-lg border border-white/10 px-3 py-2 text-center text-slate-200 hover:bg-white/10">Products</a>
          <a href="{{ \App\Support\Nav::localHref($company->comp_plan_link) }}" target="{{ $company->comp_plan_link ? '_blank' : '_self' }}" rel="noreferrer" class="rounded-lg border border-white/10 px-3 py-2 text-center text-slate-200 hover:bg-white/10">Comp Plan</a>
          <a href="{{ \App\Support\Nav::route('admin.companies.edit', $company) }}" class="rounded-lg bg-sky-500 px-3 py-2 text-center text-white hover:bg-sky-400">Manage</a>
        </div>
      </div>
    @empty
      <div class="rounded-2xl border border-dashed border-white/10 p-8 text-center text-sm text-slate-400 md:col-span-2 xl:col-span-4">
        No affiliated companies yet.
      </div>
    @endforelse
  </div>
</div>
