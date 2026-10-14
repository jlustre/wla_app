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
            // How a new member joins this company:
            // - 'link': a single public registration link, no sponsor tracking.
            // - 'sponsor_entry': the company's own registration form asks the
            //   new member to manually type in their sponsor's username/ID.
            $table->enum('signup_link_type', ['link', 'sponsor_entry'])->default('link')->after('signup_link');

            // Which identifier a sponsor should give out when signup_link_type
            // is 'sponsor_entry'. Irrelevant (left null) for 'link'.
            $table->enum('signup_sponsor_field', ['username', 'id'])->nullable()->after('signup_link_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['signup_link_type', 'signup_sponsor_field']);
        });
    }
};
