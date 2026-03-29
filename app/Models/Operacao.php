<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Operacao extends Model
{
    protected $table = 'operacoes';

    protected $fillable = [
        'codigo',
        'user_id',
        'cliente_id',
        'conveniada_id',
        'valor_requerido',
        'valor_desembolso',
        'total_juros',
        'taxa_juros',
        'taxa_multa',
        'taxa_mora',
        'status',
        'produto',
        'data_criacao',
        'data_pagamento'
    ];

    protected $casts = [
        'data_criacao' => 'date',
        'data_pagamento' => 'date',
        'valor_requerido' => 'decimal:2',
        'valor_desembolso' => 'decimal:2',
        'total_juros' => 'decimal:2',
        'taxa_juros' => 'decimal:2',
        'taxa_multa' => 'decimal:2',
        'taxa_mora' => 'decimal:2',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function conveniada(): BelongsTo
    {
        return $this->belongsTo(Conveniada::class);
    }

    public function parcelas(): HasMany
    {
        return $this->hasMany(Parcela::class);
    }

    public function historicoStatus(): HasMany
    {
        return $this->hasMany(HistoricoStatus::class);
    }
    //
}
