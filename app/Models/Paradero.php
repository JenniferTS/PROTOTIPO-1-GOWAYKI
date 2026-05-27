<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paradero extends Model
{
    protected $fillable = [
        'ruta_id',
        'nombre',
        'latitud',
        'longitud',
        'orden',
    ];

    protected $casts = [
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
    ];

    public function ruta(): BelongsTo
    {
        return $this->belongsTo(Ruta::class);
    }
}
