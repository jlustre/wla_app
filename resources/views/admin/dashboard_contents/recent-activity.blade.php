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
            <div class="flex gap-4 rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                <div class="mt-1 h-2.5 w-2.5 rounded-full bg-emerald-400"></div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-white">Maria Santos joined WLA</p>
                    <p class="mt-1 text-sm text-slate-400">Invitation accepted under Joey Lustre</p>
                </div>
                <span class="text-xs text-slate-500">5m ago</span>
            </div>
            <div class="flex gap-4 rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                <div class="mt-1 h-2.5 w-2.5 rounded-full bg-sky-400"></div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-white">Daniel Cruz joined Horizon Travel Club</p>
                    <p class="mt-1 text-sm text-slate-400">Company membership recorded successfully</p>
                </div>
                <span class="text-xs text-slate-500">18m ago</span>
            </div>
            <div class="flex gap-4 rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                <div class="mt-1 h-2.5 w-2.5 rounded-full bg-amber-400"></div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-white">Sponsorship reassigned for Angela Reyes</p>
                    <p class="mt-1 text-sm text-slate-400">Original sponsor inactive in VitalCore Wellness</p>
                </div>
                <span class="text-xs text-slate-500">42m ago</span>
            </div>
            <div class="flex gap-4 rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                <div class="mt-1 h-2.5 w-2.5 rounded-full bg-cyan-400"></div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-white">New company resource uploaded</p>
                    <p class="mt-1 text-sm text-slate-400">PureLife Supplements product brochure added</p>
                </div>
                <span class="text-xs text-slate-500">1h ago</span>
            </div>
            <div class="flex gap-4 rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                <div class="mt-1 h-2.5 w-2.5 rounded-full bg-sky-300"></div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-white">Admin updated reassignment settings</p>
                    <p class="mt-1 text-sm text-slate-400">Notification threshold changed for company positioning gaps</p>
                </div>
                <span class="text-xs text-slate-500">2h ago</span>
            </div>
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
                <input
                    type="text"
                    placeholder="Search members..."
                    class="rounded-xl border border-white/10 bg-slate-900/70 px-4 py-2.5 text-sm text-slate-200 placeholder:text-slate-500 focus:border-sky-400 focus:outline-none"
                />
                <select class="rounded-xl border border-white/10 bg-slate-900/70 px-4 py-2.5 text-sm text-slate-200 focus:border-sky-400 focus:outline-none">
                    <option>All Status</option>
                    <option>Active</option>
                    <option>Inactive</option>
                    <option>Suspended</option>
                </select>
                <a href="{{ \App\Support\Nav::route('admin.activity-logs') }}" class="inline-flex items-center justify-center rounded-xl bg-sky-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-sky-400">
                    Add Member
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
                    <tr class="hover:bg-white/[0.03]">
                        <td class="px-4 py-4 font-semibold text-white">Joey Lustre</td>
                        <td class="px-4 py-4">joey@example.com</td>
                        <td class="px-4 py-4">—</td>
                        <td class="px-4 py-4">Apr 10, 2026</td>
                        <td class="px-4 py-4"><span class="rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-semibold text-emerald-300">Active</span></td>
                        <td class="px-4 py-4">4</td>
                        <td class="px-4 py-4">18</td>
                        <td class="px-4 py-4">
                            <div class="flex gap-2">
                                <a href="{{ \App\Support\Nav::route('admin.activity-logs') }}" class="rounded-lg border border-white/10 px-3 py-1.5 text-xs font-semibold text-white hover:bg-white/10">View</a>
                                <a href="{{ \App\Support\Nav::route('admin.activity-logs') }}" class="rounded-lg border border-white/10 px-3 py-1.5 text-xs font-semibold text-white hover:bg-white/10">Edit</a>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-white/[0.03]">
                        <td class="px-4 py-4 font-semibold text-white">Maria Santos</td>
                        <td class="px-4 py-4">maria@example.com</td>
                        <td class="px-4 py-4">Joey Lustre</td>
                        <td class="px-4 py-4">Apr 16, 2026</td>
                        <td class="px-4 py-4"><span class="rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-semibold text-emerald-300">Active</span></td>
                        <td class="px-4 py-4">2</td>
                        <td class="px-4 py-4">3</td>
                        <td class="px-4 py-4">
                            <div class="flex gap-2">
                                <a href="{{ \App\Support\Nav::route('admin.activity-logs') }}" class="rounded-lg border border-white/10 px-3 py-1.5 text-xs font-semibold text-white hover:bg-white/10">View</a>
                                <a href="{{ \App\Support\Nav::route('admin.activity-logs') }}" class="rounded-lg border border-white/10 px-3 py-1.5 text-xs font-semibold text-white hover:bg-white/10">Edit</a>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-white/[0.03]">
                        <td class="px-4 py-4 font-semibold text-white">Daniel Cruz</td>
                        <td class="px-4 py-4">daniel@example.com</td>
                        <td class="px-4 py-4">Maria Santos</td>
                        <td class="px-4 py-4">Apr 14, 2026</td>
                        <td class="px-4 py-4"><span class="rounded-full bg-amber-500/10 px-3 py-1 text-xs font-semibold text-amber-300">At Risk</span></td>
                        <td class="px-4 py-4">1</td>
                        <td class="px-4 py-4">1</td>
                        <td class="px-4 py-4">
                            <div class="flex gap-2">
                                <a href="{{ \App\Support\Nav::route('admin.activity-logs') }}" class="rounded-lg border border-white/10 px-3 py-1.5 text-xs font-semibold text-white hover:bg-white/10">View</a>
                                <a href="{{ \App\Support\Nav::route('admin.activity-logs') }}" class="rounded-lg border border-red-400/20 px-3 py-1.5 text-xs font-semibold text-red-300 hover:bg-red-500/10">Suspend</a>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-white/[0.03]">
                        <td class="px-4 py-4 font-semibold text-white">Angela Reyes</td>
                        <td class="px-4 py-4">angela@example.com</td>
                        <td class="px-4 py-4">Michael Tan</td>
                        <td class="px-4 py-4">Apr 12, 2026</td>
                        <td class="px-4 py-4"><span class="rounded-full bg-red-500/10 px-3 py-1 text-xs font-semibold text-red-300">Reassigned</span></td>
                        <td class="px-4 py-4">3</td>
                        <td class="px-4 py-4">0</td>
                        <td class="px-4 py-4">
                            <div class="flex gap-2">
                                <a href="{{ \App\Support\Nav::route('admin.activity-logs') }}" class="rounded-lg border border-white/10 px-3 py-1.5 text-xs font-semibold text-white hover:bg-white/10">View</a>
                                <a href="{{ \App\Support\Nav::route('admin.activity-logs') }}" class="rounded-lg border border-white/10 px-3 py-1.5 text-xs font-semibold text-white hover:bg-white/10">Edit</a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>