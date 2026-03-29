<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'nome', 'cpf', 'data_nascimento', 'sexo', 'email'
    ];

    protected $casts = [
        'data_nascimento' => 'date',
    ];

    public function operacoes(): HasMany
    {
        return $this->hasMany(Operacao::class);
    }
    //
}
