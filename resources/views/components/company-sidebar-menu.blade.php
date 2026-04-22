<div class="space-y-1">
    @foreach($companies as $company)
        <a href="{{ route('company.dashboard', $company->id) }}"
           class="block rounded-xl px-3 py-2 text-sm text-blue-100 hover:bg-sky-600 hover:text-white"
        >
            {{ $company->name }}
        </a>
    @endforeach
</div>
