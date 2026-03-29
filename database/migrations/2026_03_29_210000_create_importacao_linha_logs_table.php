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
        Schema::create('importacao_linha_logs', function (Blueprint $table) {
            $table->id();
            $table->string('arquivo')->nullable();
            $table->unsignedInteger('linha')->index();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 20)->index(); // queued, processing, success, error
            $table->text('mensagem')->nullable();
            $table->longText('row_data')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['arquivo', 'linha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('importacao_linha_logs');
    }
};
