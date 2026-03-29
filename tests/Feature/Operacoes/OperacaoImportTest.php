<?php

namespace Tests\Feature\Operacoes;

use App\Jobs\ImportOperacoesJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class OperacaoImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_importacao_despacha_job_em_background(): void
    {
        Queue::fake();

        $user = User::factory()->create();
        $arquivo = UploadedFile::fake()->create('operacoes.xlsx', 500);

        $response = $this->actingAs($user)->post(route('operacoes.import'), [
            'arquivo' => $arquivo,
        ]);

        $response->assertRedirect(route('operacoes.index'));
        $response->assertSessionHas('status');

        Queue::assertPushed(ImportOperacoesJob::class, function (ImportOperacoesJob $job) use ($user): bool {
            return str_starts_with($job->filePath, 'imports/')
                && $job->userId === $user->id
                && $job->isAdmin === false;
        });
    }
}
