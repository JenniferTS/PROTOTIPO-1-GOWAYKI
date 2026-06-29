<?php

namespace Database\Factories;

use App\Models\Destino;
use App\Models\LugarVisitado;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LugarVisitadoFactory extends Factory
{
    protected $model = LugarVisitado::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'destino_id' => Destino::factory(),
            'fecha_visita' => fake()->dateTimeBetween('-6 months', 'now'),
            'notas' => fake()->optional(0.5)->sentence(),
        ];
    }
}
