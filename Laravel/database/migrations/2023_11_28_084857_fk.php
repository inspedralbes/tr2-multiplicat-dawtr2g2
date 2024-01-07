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
     
       Schema::table('preguntes', function (Blueprint $table) {
        $table->unsignedBigInteger('resposta_correcta_id')->nullable();
            $table->foreign('resposta_correcta_id')
                ->references('id')
                ->on('respostes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
