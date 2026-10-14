@props([
    'code',
    'title',
    'description',
    'image',
    'primaryUrl',
    'primaryLabel',
    'maxWidth' => 'max-w-2xl',
])

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $code }} — {{ $title }} | Wealth Legacy Alliance</title>
    <link rel="icon" type="image/png" href="{{ asset('images/branding/wla-favicon.png') }}">
    @vite(['resources/css/app.css'])
</head>

<body class="min-h-screen flex items-center justify-center p-4 sm:p-6" style="background:
        radial-gradient(circle at top left, rgba(16, 185, 129, 0.12), transparent 32%),
        linear-gradient(180deg, #fffdf5 0%, #f8fafc 60%, #eef2f7 100%);">
    <main class="w-full {{ $maxWidth }}">
        <h1 class="sr-only">Error {{ $code }}: {{ $title }}</h1>
        <p class="sr-only">{{ $description }}</p>

        <a href="{{ $primaryUrl }}" aria-label="{{ $primaryLabel }}"
            class="block rounded-3xl overflow-hidden shadow-xl ring-1 ring-black/5 bg-white transition hover:shadow-2xl hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2"
            style="--tw-ring-color: var(--wla-emerald-deep, #0f766e);">
            <img src="{{ asset('images/wla_error_pages/' . $image) }}" alt="" class="block h-auto w-full">
        </a>

        <nav aria-label="More options" class="mt-5 flex flex-wrap items-center justify-center gap-x-5 gap-y-2 text-sm">
            {{ $slot }}
        </nav>
    </main>
</body>

</html>
