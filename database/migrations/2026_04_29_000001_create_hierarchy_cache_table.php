<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hierarchy_cache', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ancestor_id');
            $table->unsignedBigInteger('descendant_id');
            $table->integer('depth');

            $table->unique(['ancestor_id', 'descendant_id'], 'unique_hierarchy_path');
            $table->index(['ancestor_id', 'depth'], 'idx_ancestor_depth');
            $table->index('descendant_id', 'idx_descendant');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hierarchy_cache');
    }
};
