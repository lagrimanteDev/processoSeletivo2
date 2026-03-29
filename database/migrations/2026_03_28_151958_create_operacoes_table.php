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
        Schema::create('operacoes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 50)->unique();
            $table->foreignId('cliente_id')->constrained();
            $table->foreignId('conveniada_id')->constrained();
            $table->decimal('valor_requerido', 15, 2);
            $table->decimal('valor_desembolso', 15, 2);
            $table->decimal('total_juros', 15, 2);
            $table->decimal('taxa_juros', 5, 2);
            $table->decimal('taxa_multa', 5, 2);
            $table->decimal('taxa_mora', 5, 2);
            $table->string('status', 30)->default('DIGITANDO');
            $table->string('produto', 20);
            $table->date('data_criacao');
            $table->date('data_pagamento')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operacoes');
    }
};
