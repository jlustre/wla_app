<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <style>
        body { font-family: "Inter", sans-serif; }
        .hero-overlay {
            background: linear-gradient(to right, rgba(15, 23, 42, 0.9) 30%, rgba(15, 23, 42, 0.4) 100%);
        }
        @media (max-width: 768px) {
            .hero-overlay { background: rgba(15, 23, 42, 0.75); }
        }
    </style>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <div>
                @yield('content')
            </div>
        </div>
        <script>
        const navbar = document.getElementById("navbar");
        window.onscroll = () => {
            if (window.scrollY > 50) {
                navbar.classList.add("shadow-xl", "py-2");
                navbar.classList.remove("py-4");
            } else {
                navbar.classList.add("py-4");
                navbar.classList.remove("shadow-xl", "py-2");
            }
        };
        const btn = document.getElementById("menu-btn");
        const menu = document.getElementById("mobile-menu");
        btn.addEventListener("click", () => {
            menu.classList.toggle("hidden");
        });
    </script>
    </body>
</html>
