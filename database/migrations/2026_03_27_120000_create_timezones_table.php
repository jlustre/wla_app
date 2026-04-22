<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('timezones', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g. America/New_York
            $table->string('abbreviation', 10)->nullable(); // e.g. EST
            $table->string('offset', 10)->nullable(); // e.g. -05:00
            $table->string('country_code', 3)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timezones');
    }
};
