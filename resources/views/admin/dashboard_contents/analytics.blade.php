<div class="mt-8 grid gap-6 xl:grid-cols-[1.3fr_0.7fr]">
  <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-300">Growth Analytics</p>
        <h2 class="mt-2 text-2xl font-extrabold text-white">Member Growth Over Time</h2>
      </div>
      <div class="inline-flex rounded-xl border border-white/10 bg-slate-900/70 p-1 text-sm">
        <button class="rounded-lg bg-sky-500 px-3 py-2 font-semibold text-white">10W</button>
      </div>
    </div>

    <div class="mt-8 h-72 rounded-2xl border border-white/10 bg-slate-900/60 p-5">
      <div class="flex h-full items-end gap-3">
        @foreach ($growthWeeks as $week)
          <div class="flex h-full flex-1 flex-col items-center justify-end gap-2">
            <span class="text-xs font-semibold text-slate-300">{{ $week['count'] }}</span>
            <div class="w-full rounded-t-xl bg-sky-400" style="height: {{ max($week['percent'], 4) }}%"></div>
          </div>
        @endforeach
      </div>
      <div class="mt-4 grid text-center text-xs text-slate-500" style="grid-template-columns: repeat({{ count($growthWeeks) }}, minmax(0, 1fr));">
        @foreach ($growthWeeks as $week)
          <span>{{ $week['label'] }}</span>
        @endforeach
      </div>
    </div>
  </div>

  <div class="grid gap-6">
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl">
      <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-300">Company Joins by Company</p>
      <h3 class="mt-2 text-xl font-extrabold text-white">Adoption Snapshot</h3>
      <div class="mt-6 space-y-4">
        @php $maxJoined = max(1, $companies->max('joined_members_count')); @endphp
        @forelse ($companies as $company)
          <div>
            <div class="mb-2 flex items-center justify-between text-sm">
              <span class="text-slate-300">{{ $company->name }}</span>
              <span class="font-semibold text-white">{{ number_format($company->joined_members_count) }}</span>
            </div>
            <div class="h-3 rounded-full bg-slate-800"><div class="h-3 rounded-full bg-sky-400" style="width: {{ max((int) round($company->joined_members_count / $maxJoined * 100), 2) }}%"></div></div>
          </div>
        @empty
          <p class="text-sm text-slate-400">No company membership data yet.</p>
        @endforelse
      </div>
    </div>

    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl">
      <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-300">Conversion & Reassignment</p>
      <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
        <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
          <p class="text-xs uppercase tracking-[0.18em] text-slate-400">Invitation Conversion</p>
          <p class="mt-2 text-2xl font-extrabold text-white">{{ $kpis['conversionRate'] }}%</p>
          <p class="mt-1 text-sm text-slate-400">{{ $kpis['acceptedInvitations'] }} of {{ $kpis['totalInvitations'] }} invitations accepted</p>
        </div>
        <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
          <p class="text-xs uppercase tracking-[0.18em] text-slate-400">Reassignment Trend</p>
          <p class="mt-2 text-2xl font-extrabold text-white">{{ $kpis['totalReassignments'] }}</p>
          <p class="mt-1 text-sm {{ $kpis['reassignmentsThisWeek'] > 0 ? 'text-amber-300' : 'text-slate-400' }}">{{ $kpis['reassignmentsThisWeek'] }} this week</p>
        </div>
      </div>
    </div>
  </div>
</div>
