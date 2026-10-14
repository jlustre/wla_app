<x-error-page code="401" title="Authentication Required"
    description="Please sign in to access this page. You need to be logged in to view this content."
    image="wla_401.jpg" :primary-url="route('login')" primary-label="Log In">
    <a href="{{ url('/') }}" class="font-semibold text-slate-600 transition hover:text-emerald-700">Go to Home</a>
    <span class="text-slate-300" aria-hidden="true">&bull;</span>
    <span class="text-slate-500">Don't have an account?</span>
    <a href="{{ route('register') }}" class="font-semibold text-emerald-700 underline-offset-2 hover:underline">Create
        an Account</a>
</x-error-page>
