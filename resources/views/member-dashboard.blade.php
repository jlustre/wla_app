@section('title', 'Dashboard')

@extends('layouts.member')

@section('content')
	<section class="space-y-6">
		<livewire:dashboard.dashboard-home />
	</section>
@endsection