<x-error-page code="403" title="Access Denied"
    description="This area is reserved for authorized members. You don't have permission to access this page."
    image="wla_403.jpg" :primary-url="route('dashboard')" primary-label="Go to Dashboard">
    <a href="{{ url('/') }}" class="font-semibold text-slate-600 transition hover:text-emerald-700">Go to Home</a>
</x-error-page>
