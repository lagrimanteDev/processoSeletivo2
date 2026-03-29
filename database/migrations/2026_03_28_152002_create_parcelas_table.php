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
        Schema::create('parcelas', function (Blueprint $table) {
        $table->foreignId('operacoes_id')->constrained();
        $table->integer('numero');
        $table->date('data_vencimento');
        $table->decimal('valor', 15, 2);
        $table->string('status', 20)->default('pendente');
        $table->timestamps();
        });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parcelas');
    }
};
