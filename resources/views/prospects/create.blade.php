@extends('layouts.member')

@section('title', 'Add Prospect')

@section('content')
<div class="flex items-center justify-between mb-4 mx-2 lg:mx-0">
    <h2 class="truncate text-xl font-bold text-teal-700">Add New Prospect</h2>
    <p class="mt-1 text-sm text-slate-500">Home / Add New Prospect</p>
</div>
<section class="min-h-screen bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
  <div class="max-w-2xl mx-auto">
    <div class="mb-8">
      <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('prospects.index') }}" class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-200">
          ← Back to Prospects
        </a>
        <span class="inline-flex items-center rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">Add Prospect</span>
      </div>
      <h2 class="text-3xl font-bold text-slate-900">Add New Prospect</h2>
      <p class="mt-2 text-slate-500">Fill out the form below to add a new prospect to your workspace.</p>
    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
      <form action="{{ route('prospects.store') }}" method="POST" class="p-8 space-y-8">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="first_name" class="block mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">First Name</label>
            <input id="first_name" name="first_name" type="text" required class="w-full rounded-xl border border-teal-400 bg-teal-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-teal-600 focus:ring-4 focus:ring-teal-100" />
          </div>
          <div>
            <label for="last_name" class="block mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">Last Name</label>
            <input id="last_name" name="last_name" type="text" required class="w-full rounded-xl border border-teal-400 bg-teal-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-teal-600 focus:ring-4 focus:ring-teal-100" />
          </div>
          <div>
            <label for="email" class="block mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">Email</label>
            <input id="email" name="email" type="email" required class="w-full rounded-xl border border-teal-400 bg-teal-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-teal-600 focus:ring-4 focus:ring-teal-100" />
          </div>
          <div>
            <label for="phone" class="block mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">Phone</label>
            <input id="phone" name="phone" type="text" class="w-full rounded-xl border border-teal-400 bg-teal-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-teal-600 focus:ring-4 focus:ring-teal-100" />
          </div>
          <div>
            <label for="source" class="block mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">Source</label>
            <select id="source" name="source" class="w-full rounded-xl border border-teal-400 bg-teal-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-teal-600 focus:ring-4 focus:ring-teal-100">
              <option value="">Select Source</option>
              <option>Facebook</option>
              <option>Referral</option>
              <option>YouTube</option>
              <option>Instagram</option>
              <option>TikTok</option>
            </select>
          </div>
          <!-- Stage field removed: stage is not used, timeline action is the source of truth -->
          <div>
            <label for="hotness" class="block mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">Hotness</label>
            <select id="hotness" name="hotness" class="w-full rounded-xl border border-teal-400 bg-teal-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-teal-600 focus:ring-4 focus:ring-teal-100">
              <option value="">Select Hotness</option>
              <option value="Hot">Hot</option>
              <option value="Warm">Warm</option>
              <option value="Cold">Cold</option>
              <option value="Inactive">Inactive</option>
              <option value="N/A">N/A</option>
            </select>
          </div>
          <div>
            <label for="next_follow_up" class="block mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">Next Follow-Up</label>
            <input id="next_follow_up" name="next_follow_up" type="datetime-local" class="w-full rounded-xl border border-teal-400 bg-teal-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-teal-600 focus:ring-4 focus:ring-teal-100" />
          </div>
        </div>
        <div>
          <label for="last_action" class="block mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">Last Action</label>
          <input id="last_action" name="last_action" type="text" class="w-full rounded-xl border border-teal-400 bg-teal-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-teal-600 focus:ring-4 focus:ring-teal-100" />
        </div>
        <div>
          <label for="notes" class="block mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">Notes</label>
          <textarea id="notes" name="notes" rows="3" class="w-full rounded-xl border border-teal-400 bg-teal-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-teal-600 focus:ring-4 focus:ring-teal-100"></textarea>
        </div>
        <div class="flex justify-end gap-3 pt-4">
          <a href="{{ route('prospects.index') }}" class="rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</a>
          <button type="submit" class="rounded-xl bg-sky-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-sky-600/20 transition hover:bg-sky-700">Save Prospect</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
