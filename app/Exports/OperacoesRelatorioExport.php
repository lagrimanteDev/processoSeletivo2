<?php

namespace App\Exports;

use App\Models\Operacao;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OperacoesRelatorioExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * @param Collection<int, Operacao> $operacoes
     */
    public function __construct(
        private readonly Collection $operacoes,
        private readonly CarbonInterface $exportDate,
    ) {
    }

    public function collection(): Collection
    {
        return $this->operacoes;
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            'Código da operação',
            'Nome do cliente',
            'CPF',
            'Valor da operação',
            'Status',
            'Produto',
            'Conveniada',
            'Valor Presente',
        ];
    }

    /**
     * @param Operacao $operacao
     * @return array<int, string|float>
     */
    public function map($operacao): array
    {
        $valorOperacao = $this->resolveValorOperacao($operacao);

        return [
            (string) $operacao->codigo,
            (string) ($operacao->cliente?->nome ?? ''),
            (string) ($operacao->cliente?->cpf ?? ''),
            round($valorOperacao, 2),
            (string) $operacao->status,
            (string) $operacao->produto,
            (string) ($operacao->conveniada?->nome ?? ''),
            round($this->calculatePresentValue($operacao, $valorOperacao), 2),
        ];
    }

    private function resolveValorOperacao(Operacao $operacao): float
    {
        return (float) ($operacao->valor_desembolso ?: $operacao->valor_requerido ?: 0);
    }

    private function calculatePresentValue(Operacao $operacao, float $valorOperacao): float
    {
        if ($valorOperacao <= 0) {
            return 0.0;
        }

        $taxaMensal = max((float) ($operacao->taxa_juros ?? 0), 0) / 100;

        if ($taxaMensal <= 0) {
            return $valorOperacao;
        }

        $exportDate = CarbonImmutable::parse($this->exportDate)->startOfDay();

        if (! $operacao->data_pagamento) {
            return $valorOperacao;
        }

        $paymentDate = CarbonImmutable::parse($operacao->data_pagamento)->startOfDay();
        $diasAtePagamento = $exportDate->diffInDays($paymentDate, false);

        if ($diasAtePagamento <= 0) {
            return $valorOperacao;
        }

        $fatorDesconto = pow(1 + $taxaMensal, $diasAtePagamento / 30);

        return $valorOperacao / $fatorDesconto;
    }
}
