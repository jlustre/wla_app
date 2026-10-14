@php $products = $contentSections->get('products', collect()); @endphp

<div class="rounded-3xl border border-white/10 bg-white/5 p-6 sm:p-8">
    <div class="mb-6">
        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-300">Products</p>
        <h2 class="mt-2 text-2xl font-extrabold text-white">What {{ $company->name }} Offers</h2>
        <p class="mt-1 text-sm text-slate-400">Products, catalogs, and featured offerings managed by {{ $company->name }}.</p>
    </div>

    @if($products->isEmpty())
        <div class="rounded-2xl border border-dashed border-white/15 bg-slate-900/40 p-8 text-center">
            <svg class="mx-auto h-10 w-10 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"></rect><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h10M7 16h6"></path></svg>
            <p class="mt-4 text-sm font-semibold text-white">No product information yet</p>
            <p class="mt-1 text-sm text-slate-400">{{ $company->name }} hasn't published product details here. Check their official website for the latest catalog.</p>
            @if($company->website_link)
                <a href="{{ $company->website_link }}" target="_blank" rel="noopener" class="mt-4 inline-flex items-center justify-center rounded-xl bg-sky-500 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-400">
                    Visit Website
                </a>
            @endif
        </div>
    @else
        <div class="grid gap-4 md:grid-cols-2">
            @foreach($products as $item)
                <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-5 {{ $item->element_class }}">
                    @if($item->image)
                        <img src="{{ str_starts_with($item->image, 'http') ? $item->image : asset('storage/'.$item->image) }}" alt="{{ $item->title }}" class="mb-4 h-32 w-full rounded-xl object-cover">
                    @endif
                    @if($item->title)
                        <h3 class="text-base font-bold text-white">{{ $item->title }}</h3>
                    @endif
                    @if($item->content)
                        <div class="mt-2 text-sm leading-6 text-slate-400">{!! $item->content !!}</div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
