<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ruta extends Model
{
    protected $table = 'rutas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'origen',
        'destino',
        'tiempo_estimado_minutos',
        'costo_aproximado_soles',
        'color_linea',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
        'costo_aproximado_soles' => 'decimal:2',
        'tiempo_estimado_minutos' => 'integer',
    ];

    public function paraderos(): HasMany
    {
        return $this->hasMany(Paradero::class)->orderBy('orden');
    }

    public function recorridos(): HasMany
    {
        return $this->hasMany(Recorrido::class);
    }

    public function getCostoFormateadoAttribute(): string
    {
        return 'S/ ' . number_format($this->costo_aproximado_soles, 2);
    }
}
