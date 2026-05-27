<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LugarVisitado extends Model
{
    protected $table = 'lugares_visitados';

    protected $fillable = [
        'user_id',
        'destino_id',
        'fecha_visita',
        'notas',
    ];

    protected $casts = [
        'fecha_visita' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function destino(): BelongsTo
    {
        return $this->belongsTo(Destino::class);
    }
}
