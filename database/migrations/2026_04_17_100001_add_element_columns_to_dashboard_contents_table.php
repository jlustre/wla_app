<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dashboard_contents', function (Blueprint $table) {
            $table->string('element_class')->nullable(); // for custom Tailwind/HTML classes
            $table->string('element_type')->nullable(); // e.g. 'card', 'button', 'section', etc.
        });
    }

    public function down(): void
    {
        Schema::table('dashboard_contents', function (Blueprint $table) {
            $table->dropColumn([
                'element_class',
                'element_type',
            ]);
        });
    }
};
