<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Removed duplicate softDeletes for companies (already in create table migration)
        Schema::table('dashboard_contents', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        // Removed duplicate dropSoftDeletes for companies
        Schema::table('dashboard_contents', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
