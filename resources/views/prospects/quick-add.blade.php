<div id="quick-add-prospect" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6 my-2 mx-2 lg:mx-4">
    <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h3 class="text-lg font-bold text-slate-900">Quick Add Prospect</h3>
        <p class="text-sm text-slate-500">
        New records created here will automatically belong to <span class="font-semibold text-slate-800">user_id #{{ auth()->user()->id }} ({{ auth()->user()->username }})</span>.
        </p>
    </div>
    <div class="inline-flex w-fit items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
        Owner-scoped creation
    </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-7">
    <form method="POST" action="{{ route('prospects.store') }}#quick-add-prospect" class="contents" id="quick-add-prospect-form">
        @csrf
        <div>
        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">First Name</label>
        <input name="first_name" type="text" placeholder="John" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100" />
        </div>
        <div>
        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Last Name</label>
        <input name="last_name" type="text" placeholder="Doe" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100" />
        </div>
        <div>
        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Phone</label>
        <input name="phone" type="text" placeholder="(555) 000-0000" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100" />
        </div>
        <div>
        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Email</label>
        <input name="email" type="email" placeholder="john@email.com" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100" />
        </div>
        <div>
        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Source</label>
        <select name="source" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100">
            <option value="Facebook">Facebook</option>
            <option value="Referral">Referral</option>
            <option value="YouTube">YouTube</option>
            <option value="Instagram">Instagram</option>
            <option value="TikTok">TikTok</option>
            <option value="Twitter">Twitter</option>
            <option value="LinkedIn">LinkedIn</option>
            <option value="Friend">Friend</option>
            <option value="Family" selected>Family</option>
            <option value="Other">Other</option>
        </select>
        </div>
        <div>
        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Hotness</label>
        <select name="hotness" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100">
            <option value="">N/A</option>
            <option value="100">Hot</option>
            <option value="60">Warm</option>
            <option value="20">Cold</option>
        </select>
        </div>
        <div>
        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Action</label>
        <button type="submit" class="ml-0 mt-0 inline-flex items-center justify-center rounded-xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-sky-600/20 transition hover:bg-sky-700 whitespace-nowrap">
            + Add Prospect
        </button>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('quick-add-prospect-form');
                if (form) {
                form.addEventListener('submit', function() {
                    setTimeout(function() {
                    const block = document.getElementById('quick-add-prospect');
                    if (block) block.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 100);
                });
                }
            });
            </script>
        </div>
    </form>
    </div>
        @if(session('success'))
        <div class="mt-4 mb-2 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-emerald-800 text-sm font-semibold">
            {{ session('success') }}
        </div>
        @endif
        @if($errors->any())
        <div class="mt-4 mb-2 rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 text-rose-800 text-sm font-semibold">
            {{ $errors->first() }}
        </div>
        @endif
</div>