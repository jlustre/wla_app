@php
    $categories = \App\Models\CompanyReview::CATEGORIES;
    $initialCategories = collect($categories)->keys()->mapWithKeys(
        fn ($column) => [$column => (int) old($column, $myReview->{$column} ?? 0)]
    );
    $initialHover = collect($categories)->keys()->mapWithKeys(fn ($column) => [$column => 0]);
@endphp

<div id="reviews" class="grid gap-6 lg:grid-cols-[0.9fr_1.4fr]">
    <div class="space-y-6">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 sm:p-8">
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-300">Ratings &amp; Reviews</p>

            <div class="mt-4 flex items-end gap-4">
                <span class="text-5xl font-extrabold text-white">{{ number_format($company->average_rating, 1) }}</span>
                <div class="pb-1.5">
                    <x-star-rating :rating="$company->average_rating" size="text-xl" />
                    <p class="mt-1.5 text-xs text-slate-400">
                        {{ $company->total_reviews }} {{ Str::plural('review', $company->total_reviews) }}
                    </p>
                </div>
            </div>

            <div class="mt-6 space-y-2.5">
                @foreach($ratingBreakdown as $star => $data)
                    <div class="flex items-center gap-3 text-xs">
                        <span class="w-12 shrink-0 font-medium text-slate-300">{{ $star }} star</span>
                        <div class="h-2 flex-1 overflow-hidden rounded-full bg-white/10">
                            <div class="h-full rounded-full bg-amber-400" style="width: {{ $data['percent'] }}%"></div>
                        </div>
                        <span class="w-6 shrink-0 text-right text-slate-400">{{ $data['count'] }}</span>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 border-t border-white/10 pt-6">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Average By Category</p>
                <div class="mt-3 space-y-3">
                    @foreach($categoryAverages as $column => $data)
                        <div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-medium text-slate-300">{{ $data['label'] }}</span>
                                <span class="font-semibold text-slate-200">{{ $data['average'] > 0 ? number_format($data['average'], 1) : '—' }}</span>
                            </div>
                            <div class="mt-1 h-2 overflow-hidden rounded-full bg-white/10">
                                <div class="h-full rounded-full bg-sky-400" style="width: {{ $data['average'] > 0 ? ($data['average'] / 5 * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 sm:p-8">
            <h3 class="text-lg font-bold text-white">{{ $myReview ? 'Update Your Review' : 'Write a Review' }}</h3>
            <p class="mt-1 text-sm text-slate-400">Rate {{ $company->name }} across each category to help other members decide. You can only submit one review per company — resubmitting updates it.</p>

            @if(session('status') === 'review-saved')
                <div class="mt-4 rounded-xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
                    Thanks — your review has been saved.
                </div>
            @elseif(session('status') === 'review-deleted')
                <div class="mt-4 rounded-xl border border-sky-400/30 bg-sky-500/10 px-4 py-3 text-sm text-sky-300">
                    Your review has been removed.
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('company.reviews.store', $company) }}"
                class="mt-5"
                x-data="{
                    categories: @js($initialCategories),
                    hover: @js($initialHover),
                    get allRated() {
                        return Object.values(this.categories).every(v => v > 0);
                    },
                    get overallPreview() {
                        const vals = Object.values(this.categories);
                        return this.allRated ? (vals.reduce((a, b) => a + b, 0) / vals.length).toFixed(1) : null;
                    },
                }"
            >
                @csrf

                <div class="space-y-5">
                    @foreach($categories as $column => $label)
                        <div>
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-semibold text-slate-200">{{ $label }}</label>
                                <span class="text-xs text-slate-400" x-text="categories.{{ $column }} ? categories.{{ $column }} + ' / 5' : 'Not rated'"></span>
                            </div>
                            <div class="mt-1.5 flex items-center gap-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button
                                        type="button"
                                        @click="categories.{{ $column }} = {{ $i }}"
                                        @mouseenter="hover.{{ $column }} = {{ $i }}"
                                        @mouseleave="hover.{{ $column }} = 0"
                                        class="text-2xl leading-none transition hover:scale-110"
                                        :class="(hover.{{ $column }} || categories.{{ $column }}) >= {{ $i }} ? 'text-amber-400' : 'text-white/20'"
                                        aria-label="Rate {{ $label }} {{ $i }} star{{ $i > 1 ? 's' : '' }}"
                                    >&#9733;</button>
                                @endfor
                            </div>
                            <input type="hidden" name="{{ $column }}" :value="categories.{{ $column }}">
                            @error($column)
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 flex items-center gap-2 rounded-xl border border-white/10 bg-slate-900/60 px-4 py-2.5 text-sm" x-cloak x-show="overallPreview">
                    <span class="text-slate-400">Overall rating:</span>
                    <span class="font-bold text-amber-400" x-text="overallPreview + ' / 5'"></span>
                </div>

                <label for="review" class="mt-5 block text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Your Review (optional)</label>
                <textarea
                    id="review"
                    name="review"
                    rows="4"
                    maxlength="2000"
                    placeholder="What stood out about this company's products, support, or compensation plan?"
                    class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900/60 px-4 py-3 text-sm text-slate-200 placeholder:text-slate-500 focus:border-sky-400/50 focus:outline-none focus:ring-2 focus:ring-sky-400/20"
                >{{ old('review', $myReview->review ?? '') }}</textarea>
                @error('review')
                    <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                @enderror

                <div class="mt-4 flex flex-wrap items-center gap-4">
                    <button
                        type="submit"
                        :disabled="!allRated"
                        :class="allRated ? 'bg-sky-600 hover:bg-sky-700' : 'bg-slate-700 cursor-not-allowed opacity-60'"
                        class="inline-flex items-center rounded-xl px-5 py-2.5 text-sm font-semibold text-white transition"
                    >
                        {{ $myReview ? 'Update Review' : 'Submit Review' }}
                    </button>

                    @if($myReview)
                        <button type="submit" form="delete-review-form" class="text-sm font-semibold text-red-400 hover:text-red-300">
                            Delete My Review
                        </button>
                    @endif
                </div>
            </form>

            @if($myReview)
                <form
                    id="delete-review-form"
                    method="POST"
                    action="{{ route('company.reviews.destroy', $company) }}"
                    class="hidden"
                    onsubmit="return confirm('Remove your review for {{ $company->name }}?')"
                >
                    @csrf
                    @method('DELETE')
                </form>
            @endif
        </div>

        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 sm:p-8">
            <h3 class="text-lg font-bold text-white">What Members Are Saying</h3>

            <div class="mt-5 space-y-5">
                @forelse($reviews as $review)
                    <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-sky-500/15 text-sm font-bold text-sky-300">
                                    {{ strtoupper(substr($review->user->username ?? '?', 0, 1)) }}
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-white">{{ $review->user->username ?? 'Former member' }}</p>
                                    <p class="text-xs text-slate-500">{{ $review->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <x-star-rating :rating="$review->rating" size="text-sm" />
                                <span class="text-xs font-semibold text-slate-300">{{ number_format($review->rating, 1) }}</span>
                            </div>
                        </div>

                        <div class="mt-3 grid grid-cols-2 gap-x-4 gap-y-1 sm:grid-cols-4">
                            @foreach($categories as $column => $label)
                                <div class="text-xs">
                                    <span class="text-slate-500">{{ $label }}</span>
                                    <span class="ml-1 font-semibold text-amber-400">{{ $review->{$column} }}&#9733;</span>
                                </div>
                            @endforeach
                        </div>

                        @if($review->review)
                            <p class="mt-3 text-sm leading-6 text-slate-300">{{ $review->review }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-slate-400">
                        @if($myReview)
                            No other members have reviewed {{ $company->name }} yet.
                        @else
                            No reviews yet — be the first to share your experience.
                        @endif
                    </p>
                @endforelse
            </div>

            @if($reviews->hasPages())
                <div class="mt-6">{{ $reviews->appends(['tab' => 'reviews'])->links() }}</div>
            @endif
        </div>
    </div>
</div>
