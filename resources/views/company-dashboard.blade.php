
@section('title', $company->name . ' Dashboard')

@extends('layouts.member')

@section('content')
  <div class="flex items-center justify-between mb-4 mx-2 lg:mx-0">
      <h2 class="truncate text-xl font-bold" style="color: {{ $company->primary_color ?? '#0f766e' }}">{{ $company->name }} Dashboard</h2>
      <p class="mt-1 text-sm" style="color: {{ $company->secondary_color ?? '#64748b' }}">Home / Dashboard</p>
  </div>
  <section class="min-h-screen" style="background: {{ $company->background_color ?? '#0f172a' }}; color: {{ $company->style_meta['text'] ?? '#f1f5f9' }};">
  <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8" x-data="{ tab: new URLSearchParams(window.location.search).get('tab') || 'profile', showVideoModal: false }">

    <!-- Company Hero Card (Dynamic) -->
    @include('admin.companies.hero-card')

    <!-- Tabs -->
    <div class="mt-8 border-b border-white/10">
      <nav class="-mb-px flex flex-wrap gap-x-6 gap-y-2" aria-label="Company sections">
        @foreach([
            'profile' => 'Company Profile',
            'products' => 'Products',
            'compensation' => 'Compensation Plan',
            'resources' => 'Resources',
            'reviews' => 'Ratings & Reviews',
            'invitation' => 'Invitation',
        ] as $tabKey => $tabLabel)
          <button
            type="button"
            @click="tab = '{{ $tabKey }}'"
            class="whitespace-nowrap border-b-2 px-1 pb-3 text-sm font-semibold transition"
            :class="tab === '{{ $tabKey }}' ? 'border-sky-400 text-white' : 'border-transparent text-slate-400 hover:border-white/20 hover:text-slate-200'"
          >
            {{ $tabLabel }}
          </button>
        @endforeach
      </nav>
    </div>

    <!-- Tab Panels -->
    <div class="mt-6">
      <div x-show="tab === 'profile'" x-cloak>
        @include('admin.companies.profile-tab')
      </div>

      <div x-show="tab === 'products'" x-cloak>
        @include('admin.companies.products-tab')
      </div>

      <div x-show="tab === 'compensation'" x-cloak>
        @include('admin.companies.compensation-tab')
      </div>

      <div x-show="tab === 'resources'" x-cloak>
        @include('admin.companies.resources-tab')
      </div>

      <div x-show="tab === 'reviews'" x-cloak>
        @include('admin.companies.reviews-tab')
      </div>

      <div x-show="tab === 'invitation'" x-cloak>
        @include('admin.companies.invitation-tab')
      </div>
    </div>

  </div>
</section>
@endsection
