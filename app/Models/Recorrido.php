<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recorrido extends Model
{
    protected $fillable = [
        'user_id',
        'nombre',
        'origen',
        'destino',
        'ruta_id',
        'notas',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ruta(): BelongsTo
    {
        return $this->belongsTo(Ruta::class);
    }
}
