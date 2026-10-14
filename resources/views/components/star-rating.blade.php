@props([
    'rating' => 0,
    'size' => 'text-base',
    'baseColor' => 'text-white/15',
    'fillColor' => 'text-amber-400',
    'showNumber' => false,
    'numberClass' => 'text-sm font-semibold text-slate-200',
])

@php
    $percent = max(0, min(100, ((float) $rating / 5) * 100));
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5']) }}
    role="img" aria-label="{{ number_format((float) $rating, 1) }} out of 5 stars">
    <span class="relative inline-block {{ $size }} leading-none tracking-[2px]">
        <span class="{{ $baseColor }}">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
        <span class="absolute inset-0 overflow-hidden {{ $fillColor }}" style="width: {{ $percent }}%">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
    </span>
    @if($showNumber)
        <span class="{{ $numberClass }}">{{ number_format((float) $rating, 1) }}</span>
    @endif
</span>
