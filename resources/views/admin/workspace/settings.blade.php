@extends('layouts.admin')

@section('title', 'Platform Settings')

@section('content')
<div class="w-full">
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-teal-600">Admin</p>
        <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-teal-800">Platform Settings</h1>
        <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Review core platform configuration and jump into company administration.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl bg-white p-6 shadow ring-1 ring-black/5">
            <h2 class="text-xl font-bold text-slate-900">Application</h2>
            <dl class="mt-5 space-y-4 text-sm text-slate-600">
                <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                    <dt>App name</dt>
                    <dd class="font-semibold text-slate-900">{{ $appName }}</dd>
                </div>
                <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                    <dt>Environment</dt>
                    <dd class="font-semibold capitalize text-slate-900">{{ $environment }}</dd>
                </div>
                <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                    <dt>Timezone</dt>
                    <dd class="font-semibold text-slate-900">{{ $timezone }}</dd>
                </div>
            </dl>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow ring-1 ring-black/5">
            <h2 class="text-xl font-bold text-slate-900">Directory</h2>
            <dl class="mt-5 space-y-4 text-sm text-slate-600">
                <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                    <dt>Members</dt>
                    <dd class="font-semibold text-slate-900">{{ $memberCount }}</dd>
                </div>
                <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                    <dt>Companies</dt>
                    <dd class="font-semibold text-slate-900">{{ $companyCount }}</dd>
                </div>
            </dl>
            <a href="{{ route('admin.companies.index') }}" class="mt-6 inline-flex items-center rounded-xl bg-teal-600 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-700">Manage companies</a>
        </div>
    </div>
</div>
@endsection
