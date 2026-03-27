{{-- resources/views/layouts/member.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WLA Member</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md flex flex-col">
            <div class="p-6 font-bold text-xl border-b">WLA Member</div>
            <nav class="flex-1 p-4">
                <ul class="space-y-2">
                    <li><a href="#" class="block px-3 py-2 rounded hover:bg-gray-200">Dashboard</a></li>
                    <li><a href="#" class="block px-3 py-2 rounded hover:bg-gray-200">Profile</a></li>
                    <li><a href="#" class="block px-3 py-2 rounded hover:bg-gray-200">My Sponsor</a></li>
                    <li><a href="#" class="block px-3 py-2 rounded hover:bg-gray-200">My Referrals</a></li>
                    <li><a href="#" class="block px-3 py-2 rounded hover:bg-gray-200">My Companies</a></li>
                </ul>
            </nav>
        </aside>
        <!-- Main Content -->
        <main class="flex-1 p-8">
            @yield('content')
        </main>
    </div>
</body>
</html>
