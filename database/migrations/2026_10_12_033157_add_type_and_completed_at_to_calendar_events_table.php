<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('calendar_events', function (Blueprint $table) {
            // 'event' (general, default) | 'call' | 'follow_up' | 'task'
            $table->string('type', 20)->default('event')->after('calendar_id');
            // Only meaningful for type = 'task' (a to-do style checkbox),
            // but kept generic in case other types want it later.
            $table->timestamp('completed_at')->nullable()->after('all_day');

            $table->index(['calendar_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::table('calendar_events', function (Blueprint $table) {
            $table->dropIndex(['calendar_id', 'type']);
            $table->dropColumn(['type', 'completed_at']);
        });
    }
};
