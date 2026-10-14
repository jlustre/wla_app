<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('calendar_events', function (Blueprint $table) {
            // Calls & follow-ups: who it's with, and a number to dial.
            $table->foreignId('prospect_id')->nullable()->after('calendar_id')->constrained('prospects')->nullOnDelete();
            $table->string('phone_number', 50)->nullable()->after('location');

            // Follow-ups & tasks: how urgent it is.
            $table->string('priority', 10)->nullable()->after('type'); // low | medium | high

            // Events: a virtual meeting URL.
            $table->string('meeting_link')->nullable()->after('location');
        });
    }

    public function down(): void
    {
        Schema::table('calendar_events', function (Blueprint $table) {
            $table->dropConstrainedForeignId('prospect_id');
            $table->dropColumn(['phone_number', 'priority', 'meeting_link']);
        });
    }
};
