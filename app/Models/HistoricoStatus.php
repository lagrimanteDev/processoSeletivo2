<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoricoStatus extends Model
{
    protected $table = 'historico_status';

    protected $fillable = [
        'operacao_id', 'status_anterior', 'status_novo', 'user_id'
    ];

    public function operacao(): BelongsTo
    {
        return $this->belongsTo(Operacao::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    //
}
