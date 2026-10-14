<div class="mt-8 rounded-3xl border border-emerald-400/20 bg-gradient-to-br from-emerald-500/15 via-teal-500/10 to-slate-900 p-6 shadow-2xl">
  <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
    <div>
      <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-200">Admin Utility Area</p>
      <h2 class="mt-2 text-2xl font-extrabold text-white sm:text-3xl">Keep the Platform Healthy, Positioned, and Growing</h2>
      <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-200 sm:text-base">
        Use this control center to maintain company quality, monitor sponsor risk, improve invitation conversion,
        and protect the integrity of the WLA network structure across all affiliated companies.
      </p>
    </div>

    <div class="flex flex-wrap gap-3">
      <a href="{{ \App\Support\Nav::route('admin.companies.index') }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-500">
        Manage Companies
      </a>
      <a href="{{ \App\Support\Nav::route('admin.users.index') }}" class="inline-flex items-center justify-center rounded-xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-100 hover:bg-white/10">
        Review Member Risks
      </a>
      <a href="{{ \App\Support\Nav::route('admin.reports') }}" class="inline-flex items-center justify-center rounded-xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-slate-100 hover:bg-white/10">
        Open Full Reports
      </a>
    </div>
  </div>
</div>