<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Parcela extends Model
{
    protected $table = 'parcelas';

    protected $fillable = [
        'operacao_id', 'numero', 'data_vencimento', 'valor', 'status'
    ];

    protected $casts = [
        'data_vencimento' => 'date',
        'valor' => 'decimal:2',
    ];

    public function operacao(): BelongsTo
    {
        return $this->belongsTo(Operacao::class);
    }
    //
}
