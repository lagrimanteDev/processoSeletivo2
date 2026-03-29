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
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'cpf')) {
                $table->string('cpf', 64)->nullable()->after('email')->unique();
            }

            if (! Schema::hasColumn('users', 'cliente_id')) {
                $table->foreignId('cliente_id')
                    ->nullable()
                    ->after('cpf')
                    ->constrained('clientes')
                    ->nullOnDelete();
            }
        });

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE clientes MODIFY cpf VARCHAR(64) NOT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'cliente_id')) {
                $table->dropConstrainedForeignId('cliente_id');
            }

            if (Schema::hasColumn('users', 'cpf')) {
                $table->dropUnique('users_cpf_unique');
                $table->dropColumn('cpf');
            }
        });

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE clientes MODIFY cpf VARCHAR(14) NOT NULL');
        }
    }
};
