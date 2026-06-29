<?php

namespace Database\Factories;

use App\Models\Paradero;
use App\Models\Ruta;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParaderoFactory extends Factory
{
    protected $model = Paradero::class;

    public function definition(): array
    {
        $nombres = ['Av. Ejército', 'Av. Independencia', 'Jr. San Juan', 'Av. La Marina', 'Calle Real', 'Av. Venezuela', 'Parque Central', 'Óvalo', 'Puente Nuevo', 'Mercado 2', 'Estación Central', 'Av. Dolores', 'Av. Aviación', 'Calle Comercio', 'Plaza Mayor'];

        return [
            'ruta_id' => Ruta::factory(),
            'nombre' => fake()->randomElement($nombres) . ' - ' . fake()->citySuffix(),
            'latitud' => fake()->latitude(-16.5, -16.3),
            'longitud' => fake()->longitude(-71.6, -71.4),
            'orden' => fake()->numberBetween(1, 20),
        ];
    }

    public function paraRuta(int $rutaId, int $orden): static
    {
        return $this->state(fn (array $attrs) => [
            'ruta_id' => $rutaId,
            'orden' => $orden,
        ]);
    }
}
