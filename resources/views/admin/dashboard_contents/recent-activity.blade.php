<div class="mt-8 space-y-6">
    <!-- Live Activity Feed (full width row) -->
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl">
        <div class="flex items-center justify-between">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-300">Live Activity</p>
            <h2 class="mt-2 text-2xl font-extrabold text-white">Recent Activity Feed</h2>
        </div>
        <div>
            <a href="{{ \App\Support\Nav::route('admin.activity-logs') }}" class="text-sm font-semibold text-sky-300 hover:text-sky-200">View All</a>
        </div>
        <div class="mt-6 space-y-4">
            @forelse ($activityFeed as $item)
                <div class="flex gap-4 rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                    <div class="mt-1 h-2.5 w-2.5 rounded-full {{ $item['dot'] }}"></div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-white">{{ $item['title'] }}</p>
                        <p class="mt-1 text-sm text-slate-400">{{ $item['description'] }}</p>
                    </div>
                    <span class="text-xs text-slate-500">{{ optional($item['time'])->diffForHumans() }}</span>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-white/10 p-6 text-center text-sm text-slate-400">
                    No recent platform activity yet.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Members Overview (full width row) -->
    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-300">Members Overview</p>
                <h2 class="mt-2 text-2xl font-extrabold text-white">Member Management Snapshot</h2>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ \App\Support\Nav::route('admin.users.index') }}" class="inline-flex items-center justify-center rounded-xl bg-sky-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-sky-400">
                    View All Members
                </a>
            </div>
        </div>
        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="text-slate-400">
                    <tr class="border-b border-white/10">
                        <th class="px-4 py-3 font-semibold">Name</th>
                        <th class="px-4 py-3 font-semibold">Email</th>
                        <th class="px-4 py-3 font-semibold">Sponsor</th>
                        <th class="px-4 py-3 font-semibold">Join Date</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">Active Companies</th>
                        <th class="px-4 py-3 font-semibold">Direct Referrals</th>
                        <th class="px-4 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 text-slate-300">
                    @forelse ($memberRows as $member)
                        @php
                            $statusValue = $member->status?->value ?? $member->status ?? 'active';
                            $statusClass = match ($statusValue) {
                                'active' => 'bg-emerald-500/10 text-emerald-300',
                                'suspended' => 'bg-red-500/10 text-red-300',
                                'pending' => 'bg-amber-500/10 text-amber-300',
                                default => 'bg-slate-500/10 text-slate-300',
                            };
                        @endphp
                        <tr class="hover:bg-white/[0.03]">
                            <td class="px-4 py-4 font-semibold text-white">{{ $member->username }}</td>
                            <td class="px-4 py-4">{{ $member->email }}</td>
                            <td class="px-4 py-4">{{ $member->sponsor?->username ?? '—' }}</td>
                            <td class="px-4 py-4">{{ optional($member->created_at)->format('M d, Y') }}</td>
                            <td class="px-4 py-4"><span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">{{ \Illuminate\Support\Str::headline($statusValue) }}</span></td>
                            <td class="px-4 py-4">{{ $member->active_companies_count }}</td>
                            <td class="px-4 py-4">{{ $member->direct_members_count }}</td>
                            <td class="px-4 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ \App\Support\Nav::route('admin.users.index') }}" class="rounded-lg border border-white/10 px-3 py-1.5 text-xs font-semibold text-white hover:bg-white/10">View</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-slate-400">No members yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
