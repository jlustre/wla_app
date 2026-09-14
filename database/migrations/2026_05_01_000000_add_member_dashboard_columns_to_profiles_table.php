<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->after('avatar');
            $table->string('city')->nullable()->after('phone_number');
            $table->timestamp('membership_started_at')->nullable()->after('city');
            $table->string('invite_code', 64)->nullable()->unique()->after('membership_started_at');
            $table->string('theme_preference', 16)->default('light')->after('invite_code');
            $table->unsignedTinyInteger('completion_percentage')->default(0)->after('theme_preference');
            $table->json('notification_preferences')->nullable()->after('completion_percentage');
            $table->json('privacy_preferences')->nullable()->after('notification_preferences');
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropUnique(['invite_code']);
            $table->dropColumn([
                'phone_number',
                'city',
                'membership_started_at',
                'invite_code',
                'theme_preference',
                'completion_percentage',
                'notification_preferences',
                'privacy_preferences',
            ]);
        });
    }
};