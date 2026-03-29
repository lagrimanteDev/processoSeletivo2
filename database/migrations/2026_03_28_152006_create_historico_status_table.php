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
         Schema::create('historico_status', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('operacao_id');
            $table->string('status_anterior');
            $table->string('status_novo');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historico_status');
    }
};
