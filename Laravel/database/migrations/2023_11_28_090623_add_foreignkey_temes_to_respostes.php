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
        Schema::table('respostes', function (Blueprint $table) {
            $table->unsignedBigInteger('tema_id')->nullable();
            $table->foreign('tema_id')
                ->references('id')
                ->on('temes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('respostes', function (Blueprint $table) {
            //
        });
    }
};
