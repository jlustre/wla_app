
@section('title', $company->name . ' Dashboard')

@extends('layouts.member')

@section('content')
  <div class="flex items-center justify-between mb-4 mx-2 lg:mx-0">
      <h2 class="truncate text-xl font-bold" style="color: {{ $company->primary_color ?? '#0f766e' }}">{{ $company->name }} Dashboard</h2>
      <p class="mt-1 text-sm" style="color: {{ $company->secondary_color ?? '#64748b' }}">Home / Dashboard</p>
  </div>
  <section class="min-h-screen" style="background: {{ $company->background_color ?? '#0f172a' }}; color: {{ $company->style_meta['text'] ?? '#f1f5f9' }};">
  <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
    <!-- Page Header (Dynamic) -->
    @php $hero = $company->dashboardContents->where('section', 'hero')->first(); @endphp

    <!-- Company Hero Card (Dynamic) -->
    @include('admin.companies.hero-card')

    <!-- Video Modal -->
    @include('admin.companies.video-modal')

    <!-- Quick Actions -->
    @include('admin.companies.quick-actions')

    <!-- Compensation -->
    @include('admin.companies.compensation-details')

    <!-- Resources & Training -->
     @include('admin.companies.resources-training')

  </div>
</section>
@endsection