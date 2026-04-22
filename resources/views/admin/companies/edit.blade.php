@extends('layouts.admin')

@section('title', 'Edit Company')

@section('content')
<section class="min-h-screen bg-slate-50 p-4 md:p-6 xl:p-8">
  <div class="mx-auto max-w-7xl">
    <!-- Page Header -->
    @include('admin.companies.header', ['editing' => true])

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">
      <!-- Left Content -->
      <div class="xl:col-span-9">
        @include('livewire.admin.company.form', ['company' => $company])
      </div>

      <!-- Right Sidebar -->
      @include('admin.companies.right-sidebar')
    </div>
  </div>
</section>
@endsection
