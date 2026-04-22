<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Minimal Livewire Test</title>
    @livewireStyles
</head>
<body>
    <div class="p-8">
        @yield('content')
    </div>
    @livewireScripts
</body>
</html>
