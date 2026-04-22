@extends('layouts.public')
@section('title', '500 Server Error')
@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh]">
    <h1 class="text-6xl font-bold text-red-700 mb-4">500</h1>
    <h2 class="text-2xl font-semibold mb-2">Server Error</h2>
    <p class="mb-6 text-gray-600">Whoops! Something went wrong on our servers.</p>
    <a href="/" class="text-indigo-600 hover:underline">Go Home</a>
</div>
@endsection