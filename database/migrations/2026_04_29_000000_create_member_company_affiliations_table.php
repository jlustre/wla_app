<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('member_company_affiliations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('company_id');
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();

            $table->index('user_id', 'idx_member_company_user_id');
            $table->index('company_id', 'idx_member_company_company_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('member_company_affiliations');
    }
};
