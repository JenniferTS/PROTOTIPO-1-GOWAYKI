<?php

namespace Database\Factories;

use App\Models\Recorrido;
use App\Models\Ruta;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecorridoFactory extends Factory
{
    protected $model = Recorrido::class;

    public function definition(): array
    {
        $ciudades = ['Mercado', 'Universidad', 'Plaza', 'Hospital', 'Parque', 'Terminal', 'Centro'];
        $origen = fake()->randomElement($ciudades) . ' de ' . fake()->city();
        $destino = fake()->randomElement($ciudades) . ' de ' . fake()->city();

        return [
            'user_id' => User::factory(),
            'nombre' => 'Recorrido ' . fake()->word() . ' ' . fake()->numberBetween(1, 99),
            'origen' => $origen,
            'destino' => $destino,
            'ruta_id' => Ruta::factory(),
            'notas' => fake()->optional(0.4)->sentence(),
        ];
    }
}
