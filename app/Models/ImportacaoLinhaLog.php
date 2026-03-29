<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportacaoLinhaLog extends Model
{
    protected $table = 'importacao_linha_logs';

    protected $fillable = [
        'arquivo',
        'linha',
        'user_id',
        'status',
        'mensagem',
        'row_data',
        'started_at',
        'processed_at',
    ];

    protected $casts = [
        'row_data' => 'array',
        'started_at' => 'datetime',
        'processed_at' => 'datetime',
    ];
}
