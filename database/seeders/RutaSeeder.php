<?php

namespace Database\Seeders;

use App\Models\Ruta;
use Illuminate\Database\Seeder;

class RutaSeeder extends Seeder
{
    public function run(): void
    {
        $rutas = [
            [
                'nombre' => 'Canarios S.A.',
                'descripcion' => 'Ruta que conecta el Mercado Avelino con TECSUP Arequipa, pasando por la Av. Salaverry.',
                'origen' => 'Mercado Avelino',
                'destino' => 'TECSUP Arequipa',
                'tiempo_estimado_minutos' => 25,
                'costo_aproximado_soles' => 1.00,
                'color_linea' => '#3498DB',
            ],
            [
                'nombre' => 'Cotum B',
                'descripcion' => 'Ruta desde la Plaza de Armas de Arequipa hasta el distrito de Cayma, pasando por el puente Grau.',
                'origen' => 'Plaza de Armas',
                'destino' => 'Cayma',
                'tiempo_estimado_minutos' => 35,
                'costo_aproximado_soles' => 1.00,
                'color_linea' => '#27AE60',
            ],
            [
                'nombre' => 'El Conquistador',
                'descripcion' => 'Ruta del Terminal Terrestre hacia Yanahuara, ideal para turistas.',
                'origen' => 'Terminal Terrestre',
                'destino' => 'Yanahuara',
                'tiempo_estimado_minutos' => 40,
                'costo_aproximado_soles' => 1.20,
                'color_linea' => '#F39C12',
            ],
            [
                'nombre' => 'San Martín',
                'descripcion' => 'Ruta expresa desde el Mercado San Camilo hasta el Mall Aventura.',
                'origen' => 'Mercado San Camilo',
                'destino' => 'Mall Aventura',
                'tiempo_estimado_minutos' => 20,
                'costo_aproximado_soles' => 1.00,
                'color_linea' => '#8E44AD',
            ],
            [
                'nombre' => 'Arequipa - Sachaca',
                'descripcion' => 'Ruta desde la Plaza de Armas hasta el Mirador de Sachaca, con vista al valle.',
                'origen' => 'Plaza de Armas',
                'destino' => 'Mirador de Sachaca',
                'tiempo_estimado_minutos' => 30,
                'costo_aproximado_soles' => 1.00,
                'color_linea' => '#E74C3C',
            ],
        ];

        foreach ($rutas as $ruta) {
            Ruta::create($ruta);
        }
    }
}
