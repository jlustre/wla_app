@props([
    'href' => '#',
    'spa' => true,
])

@php
    $href = \App\Support\Nav::localHref($href);
    $isPlaceholder = $href === '#' || $href === '';
    $isExternalProtocol = is_string($href) && (str_starts_with($href, 'mailto:') || str_starts_with($href, 'tel:'));
    $isExternalHost = \App\Support\Nav::isExternalHref($href);
    $shouldNavigate = $spa && ! $isPlaceholder && ! $isExternalProtocol && ! $isExternalHost;
@endphp

<a href="{{ $href }}" @if ($shouldNavigate) wire:navigate @endif {{ $attributes }}>
    {{ $slot }}
</a>
