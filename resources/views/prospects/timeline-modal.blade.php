<div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
    <div class="bg-white rounded-xl shadow-lg max-w-xl w-full p-6 relative overflow-y-auto" style="max-height:90vh;">
        <button @click="showModal = false" class="absolute top-2 right-2 text-slate-400 hover:text-slate-700">&times;</button>
        <h3 class="text-lg font-bold mb-2 text-teal-700" x-text="selected?.name"></h3>
        <div class="mb-4">
            <div class="text-sm text-slate-700"><span class="font-semibold">Email:</span> <span x-text="selected?.email"></span></div>
            <div class="text-sm text-slate-700"><span class="font-semibold">Phone:</span> <span x-text="selected?.phone"></span></div>
            <div class="text-sm text-slate-700"><span class="font-semibold">Source:</span> <span x-text="selected?.source"></span></div>
            <div class="text-sm text-slate-700"><span class="font-semibold">Hotness:</span> <span x-text="selected?.hotness"></span></div>
            <div class="text-sm text-slate-700"><span class="font-semibold">Notes:</span> <span x-text="selected?.notes"></span></div>
        </div>
        <h4 class="font-semibold text-teal-700 mb-2">Activity Timeline</h4>
        <table class="min-w-full text-sm border">
            <thead>
                <tr class="bg-slate-100">
                    <th class="px-2 py-1 border">Date</th>
                    <th class="px-2 py-1 border">Stage</th>
                    <th class="px-2 py-1 border">Notes</th>
                    <th class="px-2 py-1 border">Next Follow-Up Date</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="timeline in selected?.timelines || []" :key="timeline.id">
                    <tr>
                        <td class="px-2 py-1 border text-xs" x-text="timeline.action_at"></td>
                        <td class="px-2 py-1 border text-xs" x-text="timeline.action"></td>
                        <td class="px-2 py-1 border text-xs" x-text="timeline.notes || '-' "></td>
                        <td class="px-2 py-1 border text-xs" x-text="timeline.next_follow_up_dt ? new Date(timeline.next_follow_up_dt).toLocaleString() : '-' "></td>
                    </tr>
                </template>
                <tr x-show="(selected?.timelines || []).length === 0">
                    <td colspan="3" class="text-center text-slate-400 py-2">No activities found.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>