<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        if (Schema::hasTable('parcelas') && ! Schema::hasColumn('parcelas', 'id')) {
            Schema::table('parcelas', function (Blueprint $table): void {
                $table->id();
            });
        }

        if (Schema::hasTable('parcelas') && Schema::hasColumn('parcelas', 'operacoes_id') && ! Schema::hasColumn('parcelas', 'operacao_id')) {
            Schema::table('parcelas', function (Blueprint $table): void {
                $table->foreignId('operacao_id')->nullable()->after('operacoes_id');
            });

            DB::table('parcelas')->update([
                'operacao_id' => DB::raw('operacoes_id'),
            ]);

            Schema::table('parcelas', function (Blueprint $table): void {
                $table->dropConstrainedForeignId('operacoes_id');
            });

            Schema::table('parcelas', function (Blueprint $table): void {
                $table->foreign('operacao_id')->references('id')->on('operacoes')->cascadeOnDelete();
            });
        }

        if (Schema::hasTable('historico_status')) {
            Schema::table('historico_status', function (Blueprint $table): void {
                $table->foreign('operacao_id')->references('id')->on('operacoes')->cascadeOnDelete();
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        if (Schema::hasTable('historico_status')) {
            Schema::table('historico_status', function (Blueprint $table): void {
                $table->dropForeign(['operacao_id']);
                $table->dropForeign(['user_id']);
            });
        }

        if (Schema::hasTable('parcelas') && Schema::hasColumn('parcelas', 'operacao_id') && ! Schema::hasColumn('parcelas', 'operacoes_id')) {
            Schema::table('parcelas', function (Blueprint $table): void {
                $table->foreignId('operacoes_id')->nullable();
            });

            DB::table('parcelas')->update([
                'operacoes_id' => DB::raw('operacao_id'),
            ]);

            Schema::table('parcelas', function (Blueprint $table): void {
                $table->dropForeign(['operacao_id']);
                $table->dropColumn('operacao_id');
                $table->foreign('operacoes_id')->references('id')->on('operacoes')->cascadeOnDelete();
            });
        }
    }
};
