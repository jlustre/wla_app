
<div class="mt-8 grid gap-6 xl:grid-cols-2">
  <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-300">Resources</p>
        <h2 class="mt-2 text-2xl font-extrabold text-white">Latest Uploaded Materials</h2>
      </div>
      <a href="{{ \App\Support\Nav::route('admin.companies.index') }}" class="text-sm font-semibold text-sky-300 hover:text-sky-200">Manage Resources</a>
    </div>

    <div class="mt-6 space-y-4">
      @forelse ($resources as $resource)
        <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
          <p class="font-semibold text-white">{{ $resource->title }}</p>
          <p class="mt-1 text-sm text-slate-400">{{ \Illuminate\Support\Str::headline($resource->category ?? 'General') }} • Uploaded {{ optional($resource->created_at)->diffForHumans() }}</p>
        </div>
      @empty
        <div class="rounded-2xl border border-dashed border-white/10 p-6 text-center text-sm text-slate-400">
          No resources uploaded yet.
        </div>
      @endforelse
    </div>
  </div>

  <div class="rounded-3xl border border-white/10 bg-white/5 p-6 shadow-xl">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-300">Announcements</p>
        <h2 class="mt-2 text-2xl font-extrabold text-white">System Notices & Admin Reminders</h2>
      </div>
      <a href="{{ \App\Support\Nav::route('admin.notifications') }}" class="text-sm font-semibold text-sky-300 hover:text-sky-200">View All Notices</a>
    </div>

    <div class="mt-6 space-y-4">
      @foreach ($announcements as $announcement)
        <div class="rounded-2xl border {{ $announcement['tone'] === 'warning' ? 'border-amber-300/20 bg-amber-500/10' : 'border-white/10 bg-slate-900/60' }} p-4">
          <p class="font-semibold text-white">{{ $announcement['title'] }}</p>
          <p class="mt-1 text-sm {{ $announcement['tone'] === 'warning' ? 'text-slate-300' : 'text-slate-400' }}">{{ $announcement['description'] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</div>
