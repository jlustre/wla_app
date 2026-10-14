<x-error-page code="429" title="Too Many Requests"
    description="You've made too many requests in a short period. Please wait a moment and try again."
    image="wla_429.jpg" :primary-url="url('/')" primary-label="Go to Home">
    <a href="{{ url()->full() }}" class="font-semibold text-slate-600 transition hover:text-emerald-700">Try
        Again</a>
</x-error-page>
