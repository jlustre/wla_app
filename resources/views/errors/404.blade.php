<x-error-page code="404" title="Page Not Found"
    description="Oops! The page you're looking for seems to have flown away. It may have been moved, deleted, or you entered the wrong URL."
    image="wla_404.jpg" :primary-url="url('/')" primary-label="Go to Home">
    <a href="{{ url()->previous(url('/')) }}" class="font-semibold text-slate-600 transition hover:text-emerald-700">Go
        Back</a>
</x-error-page>
