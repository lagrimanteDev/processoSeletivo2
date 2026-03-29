<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('imports:status {--file=} {--watch} {--interval=2}', function () {
    $selectedFile = (string) ($this->option('file') ?? '');
    $watch = (bool) $this->option('watch');
    $interval = max(1, (int) $this->option('interval'));

    $render = function () use (&$selectedFile): void {
        if ($selectedFile === '') {
            $selectedFile = (string) (DB::table('importacao_linha_logs')
                ->whereNotNull('arquivo')
                ->orderByDesc('id')
                ->value('arquivo') ?? '');
        }

        $baseQuery = DB::table('importacao_linha_logs');

        if ($selectedFile !== '') {
            $baseQuery->where('arquivo', $selectedFile);
        }

        $total = (clone $baseQuery)->count();
        $queued = (clone $baseQuery)->where('status', 'queued')->count();
        $processing = (clone $baseQuery)->where('status', 'processing')->count();
        $success = (clone $baseQuery)->where('status', 'success')->count();
        $error = (clone $baseQuery)->where('status', 'error')->count();
        $lastProcessed = (clone $baseQuery)->max('processed_at');

        $jobsPendentes = DB::table('jobs')->count();
        $jobsFalhos = DB::table('failed_jobs')->count();
        $operacoes = DB::table('operacoes')->count();

        $processed = $success + $error;
        $progress = $total > 0 ? round(($processed / $total) * 100, 2) : 0;

        $this->info('=== STATUS DA IMPORTAÇÃO ===');
        $this->line('Arquivo: '.($selectedFile !== '' ? $selectedFile : 'N/A'));
        $this->line('Total de linhas: '.$total);
        $this->line('Sucesso: '.$success);
        $this->line('Erro: '.$error);
        $this->line('Em fila: '.$queued);
        $this->line('Processando: '.$processing);
        $this->line('Progresso: '.$progress.'%');
        $this->line('Última linha processada em: '.($lastProcessed ?: 'N/A'));
        $this->newLine();
        $this->line('Jobs pendentes (fila): '.$jobsPendentes);
        $this->line('Jobs falhos: '.$jobsFalhos);
        $this->line('Operações salvas: '.$operacoes);
    };

    if (! $watch) {
        $render();

        return;
    }

    while (true) {
        if (DIRECTORY_SEPARATOR === '\\') {
            system('cls');
        } else {
            system('clear');
        }

        $render();
        sleep($interval);
    }
})->purpose('Acompanha o status das importações e da fila (com opção de monitoramento contínuo).');
