<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'notificaciones_activas',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'notificaciones_activas' => 'boolean',
        ];
    }

    public function lugaresVisitados(): HasMany
    {
        return $this->hasMany(LugarVisitado::class);
    }

    public function recorridos(): HasMany
    {
        return $this->hasMany(Recorrido::class);
    }

    public function destinosVisitados()
    {
        return $this->belongsToMany(Destino::class, 'lugares_visitados')
            ->withPivot('fecha_visita', 'notas')
            ->withTimestamps();
    }

    public function progresoExploracion(): float
    {
        $total = Destino::where('activo', true)->count();
        if ($total === 0) return 0;
        return round(($this->lugaresVisitados()->count() / $total) * 100, 1);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}

