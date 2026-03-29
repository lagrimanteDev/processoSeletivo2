<?php

namespace App\Jobs;

use App\Imports\OperacoesImport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportOperacoesJob implements ShouldQueue
{
    use Queueable;

    public int $timeout = 0;

    public function __construct(
        public string $filePath,
        public ?int $userId,
        public bool $isAdmin,
    )
    {
    }

    public function handle(): void
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $import = new OperacoesImport($this->userId, $this->isAdmin);

        Excel::import($import, $this->filePath, 'local');

        Log::info('Importação de operações finalizada', [
            'arquivo' => $this->filePath,
            'criadas' => $import->created,
            'atualizadas' => $import->updated,
            'parcelas' => $import->parcelas,
            'ignoradas' => $import->skipped,
            'erros' => $import->errors,
            'primeiro_erro' => $import->errorMessages[0] ?? null,
        ]);

        Storage::disk('local')->delete($this->filePath);
    }
}
