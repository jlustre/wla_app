<div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
  <div>
    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-600">WLA Admin</p>
    <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 md:text-3xl">
      @yield('header_title', 'Company')
    </h1>
    <p class="mt-2 max-w-3xl text-sm text-slate-600 md:text-base">
      @yield('header_description', 'Use this reusable form to add or update an affiliated company inside Wealth Legacy Alliance. Organize company details, links, products, compensation, requirements, media, and WLA platform settings.')
    </p>
  </div>

  <div class="flex flex-wrap items-center gap-3">
    <a
      href="{{ route('admin.companies.index') }}"
      class="inline-flex items-center justify-center rounded-xl border border-sky-200 bg-sky-50 px-4 py-2.5 text-sm font-semibold text-sky-700 shadow-sm transition hover:bg-sky-100"
    >
      Back to Companies
    </a>
    <a href="{{ route('admin.companies.create') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-xl font-semibold shadow hover:bg-teal-700 transition">
        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Add Company
    </a>
  </div>
</div>
