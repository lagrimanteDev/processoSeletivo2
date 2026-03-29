<?php

namespace Database\Seeders;

use App\Models\Conveniada;
use Illuminate\Database\Seeder;

class ConveniadaSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $conveniadas = [
            ['codigo' => '1', 'nome' => 'Prefeitura de Leopoldina'],
            ['codigo' => '2', 'nome' => 'Prefeitura de Cataguases'],
            ['codigo' => '3', 'nome' => 'Prefeitura de Ponte Nova'],
            ['codigo' => '4', 'nome' => 'Prefeitura de Ubá'],
            ['codigo' => '5', 'nome' => 'Prefeitura de Muriaé'],
            ['codigo' => '6', 'nome' => 'Exército de Leopoldina'],
            ['codigo' => '7', 'nome' => 'Exército de Cataguases'],
            ['codigo' => '8', 'nome' => 'Governo de SP'],
            ['codigo' => '9', 'nome' => 'Prefeitura de Goiânia'],
            ['codigo' => '10', 'nome' => 'Prefeitura de São Paulo'],
        ];

        foreach ($conveniadas as $conveniada) {
            Conveniada::updateOrCreate(
                ['codigo' => $conveniada['codigo']],
                ['nome' => $conveniada['nome']]
            );
        }
    }
}
