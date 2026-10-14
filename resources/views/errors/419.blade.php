<x-error-page code="419" title="Session Expired"
    description="Your session has expired for security reasons. Please sign in again to continue."
    image="wla_419.jpg" :primary-url="route('login')" primary-label="Log In Again">
    <a href="{{ url('/') }}" class="font-semibold text-slate-600 transition hover:text-emerald-700">Go to Home</a>
</x-error-page>
