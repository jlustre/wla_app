@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="w-full">
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-teal-600">Admin</p>
        <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-teal-800">{{ $title }}</h1>
        <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">{{ $description }}</p>
    </div>

    @isset($metrics)
        <div class="mb-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($metrics as $metric)
                <div class="rounded-2xl bg-white p-5 shadow ring-1 ring-black/5">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">{{ $metric['label'] }}</p>
                    <p class="mt-3 text-3xl font-extrabold text-slate-900">{{ $metric['value'] }}</p>
                </div>
            @endforeach
        </div>
    @endisset

    @isset($cards)
        <div class="mb-8 grid gap-6 lg:grid-cols-2">
            @foreach ($cards as $card)
                <article class="rounded-2xl bg-white p-6 shadow ring-1 ring-black/5">
                    <h2 class="text-xl font-bold text-slate-900">{{ $card['title'] }}</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ $card['body'] }}</p>
                </article>
            @endforeach
        </div>
    @endisset

    @isset($columns)
        <div class="overflow-x-auto rounded-2xl bg-white shadow ring-1 ring-black/5">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        @foreach ($columns as $column)
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-700">{{ $column }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($rows as $row)
                        <tr class="hover:bg-slate-50">
                            @foreach ($row as $cell)
                                <td class="px-6 py-4 text-sm text-slate-700">{{ $cell }}</td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) }}" class="px-6 py-10 text-center text-sm text-slate-500">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endisset

    @isset($paginator)
        <div class="mt-6">{{ $paginator->links() }}</div>
    @endisset
</div>
@endsection
