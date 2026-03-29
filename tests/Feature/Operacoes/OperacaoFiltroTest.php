<?php

namespace Tests\Feature\Operacoes;

use App\Models\Cliente;
use App\Models\Conveniada;
use App\Models\Operacao;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OperacaoFiltroTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_pode_filtrar_operacoes_por_cpf(): void
    {
        $user = User::factory()->create();
        $conveniada = Conveniada::create([
            'codigo' => 'CONV-01',
            'nome' => 'Conveniada Teste',
        ]);

        $clienteAlvo = Cliente::create([
            'nome' => 'Cliente Alvo',
            'cpf' => '12345678901',
            'data_nascimento' => '1990-01-01',
            'sexo' => 'M',
            'email' => 'alvo@teste.com',
        ]);

        $clienteOutro = Cliente::create([
            'nome' => 'Cliente Outro',
            'cpf' => '99999999999',
            'data_nascimento' => '1991-01-01',
            'sexo' => 'F',
            'email' => 'outro@teste.com',
        ]);

        Operacao::create([
            'codigo' => 'OP-CPF-001',
            'user_id' => $user->id,
            'cliente_id' => $clienteAlvo->id,
            'conveniada_id' => $conveniada->id,
            'valor_requerido' => 1000,
            'valor_desembolso' => 900,
            'total_juros' => 100,
            'taxa_juros' => 10,
            'taxa_multa' => 2,
            'taxa_mora' => 1,
            'status' => 'DIGITANDO',
            'produto' => 'CREDITO',
            'data_criacao' => '2026-03-01',
            'data_pagamento' => null,
        ]);

        Operacao::create([
            'codigo' => 'OP-CPF-002',
            'user_id' => $user->id,
            'cliente_id' => $clienteOutro->id,
            'conveniada_id' => $conveniada->id,
            'valor_requerido' => 2000,
            'valor_desembolso' => 1800,
            'total_juros' => 200,
            'taxa_juros' => 10,
            'taxa_multa' => 2,
            'taxa_mora' => 1,
            'status' => 'DIGITANDO',
            'produto' => 'CREDITO',
            'data_criacao' => '2026-03-01',
            'data_pagamento' => null,
        ]);

        $response = $this->actingAs($user)->get(route('operacoes.index', [
            'cpf' => '12345678901',
        ]));

        $response->assertOk();
        $response->assertSee('OP-CPF-001');
        $response->assertDontSee('OP-CPF-002');
    }

    public function test_usuario_pode_filtrar_operacoes_por_cpf_alfanumerico(): void
    {
        $user = User::factory()->create();
        $conveniada = Conveniada::create([
            'codigo' => 'CONV-02',
            'nome' => 'Conveniada Teste 2',
        ]);

        $clienteAlvo = Cliente::create([
            'nome' => 'Cliente Alfanumérico',
            'cpf' => 'zyA5BQVXFmqjaP',
            'data_nascimento' => '1992-01-01',
            'sexo' => 'M',
            'email' => 'alfa@teste.com',
        ]);

        $clienteOutro = Cliente::create([
            'nome' => 'Cliente Numérico',
            'cpf' => '11122233344',
            'data_nascimento' => '1993-01-01',
            'sexo' => 'F',
            'email' => 'num@teste.com',
        ]);

        Operacao::create([
            'codigo' => 'OP-ALFA-001',
            'user_id' => $user->id,
            'cliente_id' => $clienteAlvo->id,
            'conveniada_id' => $conveniada->id,
            'valor_requerido' => 1500,
            'valor_desembolso' => 1400,
            'total_juros' => 100,
            'taxa_juros' => 10,
            'taxa_multa' => 2,
            'taxa_mora' => 1,
            'status' => 'DIGITANDO',
            'produto' => 'CREDITO',
            'data_criacao' => '2026-03-01',
            'data_pagamento' => null,
        ]);

        Operacao::create([
            'codigo' => 'OP-ALFA-002',
            'user_id' => $user->id,
            'cliente_id' => $clienteOutro->id,
            'conveniada_id' => $conveniada->id,
            'valor_requerido' => 1500,
            'valor_desembolso' => 1400,
            'total_juros' => 100,
            'taxa_juros' => 10,
            'taxa_multa' => 2,
            'taxa_mora' => 1,
            'status' => 'DIGITANDO',
            'produto' => 'CREDITO',
            'data_criacao' => '2026-03-01',
            'data_pagamento' => null,
        ]);

        $response = $this->actingAs($user)->get(route('operacoes.index', [
            'cpf' => 'zyA-5BQVXFmqjaP',
        ]));

        $response->assertOk();
        $response->assertSee('OP-ALFA-001');
        $response->assertDontSee('OP-ALFA-002');
    }
}
