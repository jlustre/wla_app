@extends('layouts.minimal')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Livewire Test</h1>
    @livewire('test-livewire')
    <div>
        <div class="mb-4">Test value: <span class="font-bold">{{ $test }}</span></div>
        <button wire:click="updateTest" class="px-4 py-2 bg-blue-600 text-white rounded">Update Test</button>
    </div>
@endsection
