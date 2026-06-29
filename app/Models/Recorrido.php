<?php

namespace App\Models;

use Database\Factories\RecorridoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recorrido extends Model
{
    /** @use HasFactory<RecorridoFactory> */
    use HasFactory;

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
