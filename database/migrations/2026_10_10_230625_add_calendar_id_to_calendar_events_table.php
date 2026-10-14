<?php

use App\Models\Calendar;
use App\Models\CalendarEvent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('calendar_events', function (Blueprint $table) {
            $table->foreignId('calendar_id')->nullable()->after('user_id')->constrained()->cascadeOnDelete();
        });

        // Backfill: every user who already has events gets a default
        // "My Calendar", and their existing events are attached to it.
        // calendar_id stays nullable at the DB level (simpler, no need for
        // a doctrine/dbal-dependent column alter) — the application always
        // sets it on create.
        $userIds = CalendarEvent::whereNull('calendar_id')->distinct()->pluck('user_id');

        foreach ($userIds as $userId) {
            $calendar = Calendar::firstOrCreate(
                ['user_id' => $userId, 'is_default' => true],
                ['name' => 'My Calendar', 'color' => 'peacock']
            );

            CalendarEvent::where('user_id', $userId)
                ->whereNull('calendar_id')
                ->update(['calendar_id' => $calendar->id]);
        }

        // Color now optionally overrides the calendar's own color, so the
        // previous blanket 'peacock' default no longer means anything.
        Schema::table('calendar_events', function (Blueprint $table) {
            $table->string('color', 20)->nullable()->default(null)->change();
        });
        DB::table('calendar_events')->where('color', 'peacock')->update(['color' => null]);
    }

    public function down(): void
    {
        Schema::table('calendar_events', function (Blueprint $table) {
            $table->dropConstrainedForeignId('calendar_id');
            $table->string('color', 20)->default('peacock')->nullable(false)->change();
        });
    }
};
