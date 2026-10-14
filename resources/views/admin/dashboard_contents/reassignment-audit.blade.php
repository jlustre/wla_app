<div class="mt-8 rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl">
  <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-300">Audit Snapshot</p>
      <h2 class="mt-2 text-2xl font-extrabold text-white">Recent Sponsorship Reassignments</h2>
    </div>
    <a href="{{ \App\Support\Nav::route('admin.audit-logs.index') }}" class="text-sm font-semibold text-sky-300 hover:text-sky-200">Open Full Audit Log</a>
  </div>

  <div class="mt-6 overflow-x-auto">
    <table class="min-w-full text-left text-sm">
      <thead class="text-slate-400">
        <tr class="border-b border-white/10">
          <th class="px-4 py-3 font-semibold">Date</th>
          <th class="px-4 py-3 font-semibold">Member</th>
          <th class="px-4 py-3 font-semibold">Original Sponsor</th>
          <th class="px-4 py-3 font-semibold">New Sponsor</th>
          <th class="px-4 py-3 font-semibold">Reason</th>
          <th class="px-4 py-3 font-semibold">Details</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-white/10 text-slate-300">
        @forelse ($reassignments as $row)
          <tr class="hover:bg-white/[0.03]">
            <td class="px-4 py-4">{{ $row['date'] }}</td>
            <td class="px-4 py-4 font-semibold text-white">{{ $row['member'] }}</td>
            <td class="px-4 py-4">{{ $row['original_sponsor'] }}</td>
            <td class="px-4 py-4">{{ $row['new_sponsor'] }}</td>
            <td class="px-4 py-4">{{ $row['reason'] }}</td>
            <td class="px-4 py-4"><a href="{{ \App\Support\Nav::route('admin.audit-logs.index') }}" class="font-semibold text-sky-300 hover:text-sky-200">View</a></td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="px-4 py-8 text-center text-slate-400">No sponsorship reassignments recorded yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
