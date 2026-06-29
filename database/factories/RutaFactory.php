<?php

namespace Database\Factories;

use App\Models\Ruta;
use Illuminate\Database\Eloquent\Factories\Factory;

class RutaFactory extends Factory
{
    protected $model = Ruta::class;

    public function definition(): array
    {
        $origenes = ['Mercado Central', 'Plaza San Francisco', 'Terminal Terrestre', 'Estación de Ferrocarril', 'Parque Industrial', 'Cerro Colorado', 'Paucarpata', 'Miraflores', 'Cayma', 'Yanahuara'];
        $destinos = ['Mall Plaza', 'Universidad', 'Hospital Regional', 'Zona Sur', 'Parque Metropolitano', 'Ciudad Satélite', 'Alto Selva Alegre', 'Sachaca', 'Tiabaya', 'Hunter'];

        return [
            'nombre' => fake()->company() . ' ' . fake()->randomElement(['Express', 'Directo', 'Rápido', 'VIP', 'Norte', 'Sur']),
            'descripcion' => fake()->sentence(12),
            'origen' => fake()->randomElement($origenes),
            'destino' => fake()->randomElement($destinos),
            'tiempo_estimado_minutos' => fake()->numberBetween(15, 60),
            'costo_aproximado_soles' => fake()->randomFloat(2, 0.80, 2.50),
            'color_linea' => fake()->hexColor(),
            'activa' => false,
        ];
    }

    public function activa(): static
    {
        return $this->state(fn (array $attrs) => ['activa' => true]);
    }
}
