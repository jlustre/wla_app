{{-- resources/views/layouts/public.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wealth Legacy Alliance</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen">
    <header class="bg-white shadow">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <div class="font-bold text-2xl">WLA</div>
            <nav>
                <ul class="flex space-x-6">
                    <li><a href="#" class="hover:text-blue-600">Home</a></li>
                    <li><a href="#" class="hover:text-blue-600">About</a></li>
                    <li><a href="#" class="hover:text-blue-600">How It Works</a></li>
                    <li><a href="#" class="hover:text-blue-600">Companies</a></li>
                    <li><a href="#" class="hover:text-blue-600">FAQ</a></li>
                    <li><a href="#" class="hover:text-blue-600">Contact</a></li>
                    <li><a href="#" class="hover:text-blue-600">Join</a></li>
                    <li><a href="#" class="hover:text-blue-600">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>
</body>

</html>