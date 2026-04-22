@props(['company' => null])

@php
    $isEdit = !is_null($company);
@endphp

<form class="space-y-6">
    <!-- Basic Information -->
    @livewire('admin.companies.basic-information', ['companyId' => $company ? $company->id : null], key($company ? 'company-edit-'.$company->id : 'company-create'))

    <!-- Company Links -->
    @include('admin.companies.links')

    <!-- Training & Resources -->
    @include('admin.companies.training-resources')

    <!-- Sticky action area for mobile / bottom -->
    @include('admin.companies.sticky-actions')
</form>
