<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete();
            $table->foreignId('state_province_id')->nullable()->constrained('states_provinces')->nullOnDelete();
            $table->foreignId('timezone_id')->nullable()->constrained('timezones')->nullOnDelete();
            $table->string('bio')->nullable();
            $table->string('avatar')->nullable();
            $table->dateTime('last_login_dt')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
