<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conveniada extends Model
{
    protected $table = 'conveniadas';

    protected $fillable = ['codigo', 'nome'];

    public function operacoes(): HasMany
    {
        return $this->hasMany(Operacao::class);
    }
    //
}
