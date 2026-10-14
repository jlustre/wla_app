<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('company_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Per-category star ratings (1-5). Kept as explicit columns, matching
            // App\Models\CompanyReview::CATEGORIES, rather than a normalized table,
            // since the category list is fixed and this keeps aggregation simple.
            $table->unsignedTinyInteger('compensation_rating');
            $table->unsignedTinyInteger('products_rating');
            $table->unsignedTinyInteger('support_rating');
            $table->unsignedTinyInteger('leadership_rating');

            // Overall rating, auto-computed as the average of the 4 category
            // ratings (see CompanyReview::booted()). Stored so company-level
            // aggregates (withAvg/withCount) stay a single cheap column read.
            $table->decimal('rating', 2, 1)->unsigned();

            $table->text('review')->nullable();
            $table->timestamps();

            // A member can only leave one review per company (resubmitting updates it).
            $table->unique(['company_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_reviews');
    }
};
