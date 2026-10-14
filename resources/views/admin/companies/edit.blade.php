@extends('layouts.admin')

@section('title', 'Edit Company')

@section('content')
<section class="space-y-6">
    <a href="{{ route('admin.companies.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-sky-700 hover:text-sky-900">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" /></svg>
        Back to Companies
    </a>

    <div class="overflow-hidden rounded-[36px] border border-slate-200/80 bg-slate-950 text-slate-100 shadow-[0_30px_100px_-55px_rgba(15,23,42,0.65)] dark:border-slate-800">
        <div class="px-4 py-6 sm:px-6 lg:px-8">
            <livewire:admin.company.onboarding :company-id="$company->id" :key="'company-onboarding-'.$company->id" />
        </div>
    </div>
</section>
@endsection
