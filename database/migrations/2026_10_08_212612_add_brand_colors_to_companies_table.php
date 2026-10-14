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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('primary_color', 20)->nullable()->after('status');
            $table->string('secondary_color', 20)->nullable()->after('primary_color');
            $table->string('highlight_color', 20)->nullable()->after('secondary_color');
            $table->string('background_color', 20)->nullable()->after('highlight_color');
            $table->json('style_meta')->nullable()->after('background_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['primary_color', 'secondary_color', 'highlight_color', 'background_color', 'style_meta']);
        });
    }
};
