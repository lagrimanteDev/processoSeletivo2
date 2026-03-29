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
        if (Schema::hasTable('operacoes') && ! Schema::hasColumn('operacoes', 'user_id')) {
            Schema::table('operacoes', function (Blueprint $table): void {
                $table->foreignId('user_id')->nullable()->after('codigo')->constrained('users')->nullOnDelete();
                $table->index(['user_id', 'id'], 'operacoes_user_id_id_index');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('operacoes') && Schema::hasColumn('operacoes', 'user_id')) {
            Schema::table('operacoes', function (Blueprint $table): void {
                $table->dropIndex('operacoes_user_id_id_index');
                $table->dropConstrainedForeignId('user_id');
            });
        }
    }
};
