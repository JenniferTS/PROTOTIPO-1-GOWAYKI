<?php

namespace Database\Factories;

use App\Models\Destino;
use Illuminate\Database\Eloquent\Factories\Factory;

class DestinoFactory extends Factory
{
    protected $model = Destino::class;

    public function definition(): array
    {
        $categorias = ['turistico', 'cultural', 'gastronomico', 'recreativo', 'historico'];
        $distritos = ['Cercado', 'Yanahuara', 'Cayma', 'Sachaca', 'Tiabaya', 'Hunter', 'Miraflores', 'Paucarpata', 'Cerro Colorado', 'José Luis Bustamante'];

        return [
            'nombre' => fake()->streetSuffix() . ' de ' . fake()->colorName(),
            'tagline' => fake()->catchPhrase(),
            'descripcion' => fake()->paragraph(4),
            'categoria' => fake()->randomElement($categorias),
            'distrito' => fake()->randomElement($distritos),
            'latitud' => fake()->latitude(-16.5, -16.3),
            'longitud' => fake()->longitude(-71.6, -71.4),
            'imagen_url' => null,
            'calificacion' => fake()->randomFloat(2, 3.0, 5.0),
            'activo' => false,
        ];
    }

    public function activo(): static
    {
        return $this->state(fn (array $attrs) => ['activo' => true]);
    }
}
