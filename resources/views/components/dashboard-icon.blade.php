@props(['name', 'class' => 'h-5 w-5'])

@switch($name)
    @case('home')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10.75 12 3l9 7.75" /><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 9.5V20.25H18.75V9.5" /></svg>
        @break
    @case('user')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5a7.5 7.5 0 0 1 15 0" /></svg>
        @break
    @case('shield')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3.75 5.25 6v5.25c0 4.79 2.72 8.48 6.75 9.75 4.03-1.27 6.75-4.96 6.75-9.75V6L12 3.75Z" /></svg>
        @break
    @case('link')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.69 15.31 6.56a4.5 4.5 0 1 1 6.36 6.36l-2.12 2.12" /><path stroke-linecap="round" stroke-linejoin="round" d="M10.81 15.31 8.69 17.44a4.5 4.5 0 1 1-6.36-6.36l2.12-2.12" /><path stroke-linecap="round" stroke-linejoin="round" d="m8.69 15.31 6.62-6.62" /></svg>
        @break
    @case('users')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.5a5.25 5.25 0 0 0-10.5 0" /><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 11.25a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5a5.25 5.25 0 0 0-4.125-5.121" /><path stroke-linecap="round" stroke-linejoin="round" d="M14.625 5.156a3 3 0 0 1 0 5.688" /></svg>
        @break
    @case('network')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v6" /><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 19.5h10.5" /><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 10.5 12 14.25l3.75-3.75" /><circle cx="12" cy="4.5" r="1.5" fill="currentColor" stroke="none" /><circle cx="6.75" cy="19.5" r="1.5" fill="currentColor" stroke="none" /><circle cx="17.25" cy="19.5" r="1.5" fill="currentColor" stroke="none" /></svg>
        @break
    @case('academic-cap')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 9.75 7.5-4.5 7.5 4.5-7.5 4.5-7.5-4.5Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 11.25v3.75c0 1.5 2.25 3 5.25 3s5.25-1.5 5.25-3v-3.75" /></svg>
        @break
    @case('chip')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="7.5" y="7.5" width="9" height="9" rx="1.5" /><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 1.5v3M14.25 1.5v3M9.75 19.5v3M14.25 19.5v3M19.5 9.75h3M19.5 14.25h3M1.5 9.75h3M1.5 14.25h3" /></svg>
        @break
    @case('presentation-chart')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 4.5h15v10.5h-15Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 6 19.5h12L15.75 15" /><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 11.25 10.5 9l2.25 2.25 3-3" /></svg>
        @break
    @case('play')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="currentColor"><path d="M8.25 6.75v10.5L17.25 12 8.25 6.75Z" /></svg>
        @break
    @case('folder')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75A2.25 2.25 0 0 1 6 4.5h3.128a2.25 2.25 0 0 1 1.59.659l1.372 1.372A2.25 2.25 0 0 0 13.68 7.2H18A2.25 2.25 0 0 1 20.25 9.45v8.55A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6.75Z" /></svg>
        @break
    @case('calendar')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3.75" y="5.25" width="16.5" height="15" rx="2.25" /><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 3.75v3M16.5 3.75v3M3.75 9.75h16.5" /></svg>
        @break
    @case('bell')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M14.25 18.75a2.25 2.25 0 0 1-4.5 0" /><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 15.75h13.5l-1.5-1.5v-3.75a5.25 5.25 0 1 0-10.5 0v3.75l-1.5 1.5Z" /></svg>
        @break
    @case('life-buoy')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8.25" /><circle cx="12" cy="12" r="2.25" /><path stroke-linecap="round" stroke-linejoin="round" d="M17.834 6.166 14.25 9.75M9.75 14.25l-3.584 3.584M17.834 17.834 14.25 14.25M9.75 9.75 6.166 6.166" /></svg>
        @break
    @case('cog')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.114-.94h1.086c.554 0 1.024.398 1.114.94l.18 1.081c.058.348.283.643.604.798.321.155.699.152 1.016-.007l.981-.492c.5-.25 1.105-.091 1.42.372l.543.797c.315.463.244 1.086-.167 1.468l-.808.75a1.125 1.125 0 0 0-.328 1.028c.067.349.27.657.56.845l.951.618c.464.301.65.897.436 1.396l-.369.858c-.214.499-.762.78-1.297.665l-1.1-.236a1.125 1.125 0 0 0-1.014.253c-.272.229-.431.566-.431.921v1.126c0 .547-.388 1.014-.926 1.114l-.929.172c-.538.1-1.07-.195-1.263-.701l-.397-1.044a1.125 1.125 0 0 0-.757-.684 1.125 1.125 0 0 0-1.004.155l-.93.62c-.455.303-1.068.219-1.425-.197l-.621-.725c-.357-.416-.345-1.035.027-1.438l.76-.823c.239-.259.352-.611.308-.962a1.125 1.125 0 0 0-.498-.806l-.962-.642c-.462-.308-.64-.908-.418-1.409l.382-.862c.221-.5.774-.774 1.309-.649l1.07.25c.343.08.704-.004.975-.227.272-.223.423-.562.408-.913l-.046-1.136c-.022-.548.349-1.03.887-1.152l.926-.21Z" /><circle cx="12" cy="12" r="3" /></svg>
        @break
    @case('clipboard-document-list')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 4.5h6A2.25 2.25 0 0 1 17.25 6.75v12A2.25 2.25 0 0 1 15 21H9a2.25 2.25 0 0 1-2.25-2.25v-12A2.25 2.25 0 0 1 9 4.5Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25h6M9 12h6M9 15.75h3" /></svg>
        @break
    @case('magnifying-glass')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35" /><circle cx="10.5" cy="10.5" r="6.75" /></svg>
        @break
    @case('chat-bubble-left-right')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9M7.5 12h5.25M6.75 3.75h10.5a3 3 0 0 1 3 3v6a3 3 0 0 1-3 3h-5.19l-4.56 3.42c-.495.372-1.2.019-1.2-.6v-2.82A3 3 0 0 1 3.75 12.75v-6a3 3 0 0 1 3-3Z" /></svg>
        @break
    @case('check-circle')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8.25" /><path stroke-linecap="round" stroke-linejoin="round" d="m8.625 12 2.25 2.25L15.75 9.375" /></svg>
        @break
    @case('clock')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8.25" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5v4.5l3 1.5" /></svg>
        @break
    @case('arrow-right-on-rectangle')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.625A2.625 2.625 0 0 0 13.125 3h-5.25A2.625 2.625 0 0 0 5.25 5.625v12.75A2.625 2.625 0 0 0 7.875 21h5.25a2.625 2.625 0 0 0 2.625-2.625V15" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 12h9m0 0-3-3m3 3-3 3" /></svg>
        @break
    @default
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8.25" /></svg>
@endswitch