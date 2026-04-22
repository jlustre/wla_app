@extends('layouts.member')

@section('content')
<h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
    {{ __('Profile') }}
</h2>
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <livewire:profile.profile-page>

    </div>
</div>
@endsection