<x-error-page code="503" title="Service Unavailable"
    description="We're making improvements to serve you better. Our site is temporarily unavailable. Please check back soon."
    image="wla_503.jpg" :primary-url="url()->full()" primary-label="Check Back Later" max-width="max-w-4xl">
    <a href="{{ url('/') }}" class="font-semibold text-slate-600 transition hover:text-emerald-700">Go to Home</a>
</x-error-page>
