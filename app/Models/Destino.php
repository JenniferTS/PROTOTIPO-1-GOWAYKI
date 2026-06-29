<?php

namespace App\Models;

use Database\Factories\DestinoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destino extends Model
{
    /** @use HasFactory<DestinoFactory> */
    use HasFactory;

    protected $fillable = [
        'nombre',
        'tagline',
        'descripcion',
        'categoria',
        'distrito',
        'latitud',
        'longitud',
        'imagen_url',
        'calificacion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'calificacion' => 'decimal:2',
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
    ];

    public function lugaresVisitados(): HasMany
    {
        return $this->hasMany(LugarVisitado::class);
    }

    public function visitadoPor(User $user): bool
    {
        return $this->lugaresVisitados()->where('user_id', $user->id)->exists();
    }
}
