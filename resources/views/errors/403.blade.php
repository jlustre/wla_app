@extends('layouts.public')
@section('title', '403 Forbidden')
@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh]">
    <h1 class="text-6xl font-bold text-red-600 mb-4">403</h1>
    <h2 class="text-2xl font-semibold mb-2">Forbidden</h2>
    <p class="mb-6 text-gray-600">You do not have permission to access this page.</p>
    <a href="/" class="text-indigo-600 hover:underline">Go Home</a>
</div>
@endsection