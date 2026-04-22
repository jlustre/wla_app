@extends('layouts.public')
@section('title', '404 Not Found')
@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh]">
    <h1 class="text-6xl font-bold text-yellow-500 mb-4">404</h1>
    <h2 class="text-2xl font-semibold mb-2">Page Not Found</h2>
    <p class="mb-6 text-gray-600">Sorry, the page you are looking for could not be found.</p>
    <a href="/" class="text-indigo-600 hover:underline">Go Home</a>
</div>
@endsection