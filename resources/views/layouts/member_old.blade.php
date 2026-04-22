{{-- resources/views/layouts/member.blade.php --}}
<!DOCTYPE html>
<html lang="en">

@include('layouts.partials.head-css')

<body class="bg-gray-100 min-h-screen">
    @include('components.topbar')
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <livewire:components.member-sidebar />
        <!-- Main Content -->
        <main class="flex-1 p-8">
            @yield('content')
        </main>
    </div>
</body>

</html>