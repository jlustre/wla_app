<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @include('layouts.partials.theme-script')

        <link rel="icon" type="image/png" href="{{ asset('images/branding/wla-favicon.png') }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased dark:text-slate-100">
        <div
            class="relative flex min-h-screen flex-col items-center bg-slate-950 pt-6 sm:justify-center sm:pt-0"
            style="background-image: linear-gradient(rgba(6, 21, 56, 0.84), rgba(6, 21, 56, 0.84)), url('/images/background.png'); background-size: cover; background-position: center;"
        >
            <div class="fixed right-4 top-4 z-10">
                <livewire:dashboard.theme-toggle />
            </div>

            <div class="mt-1 w-full overflow-hidden bg-white px-2 py-2 shadow-md transition-colors duration-300 dark:bg-slate-900 sm:max-w-md sm:rounded-lg md:px-4 md:py-4">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
