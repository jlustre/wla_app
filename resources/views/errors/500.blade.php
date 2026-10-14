<x-error-page code="500" title="Something Went Wrong"
    description="We encountered an unexpected problem on our server. Our team has been notified and is working to fix it."
    image="wla_500.jpg" :primary-url="url()->full()" primary-label="Try Again">
    <a href="{{ url('/') }}" class="font-semibold text-slate-600 transition hover:text-emerald-700">Go to Home</a>
</x-error-page>
