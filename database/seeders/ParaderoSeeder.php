<?php

namespace Database\Seeders;

use App\Models\Paradero;
use App\Models\Ruta;
use Illuminate\Database\Seeder;

class ParaderoSeeder extends Seeder
{
    public function run(): void
    {
        $paraderos = [
            // Canarios S.A. (ruta 1)
            ['ruta_nombre' => 'Canarios S.A.', 'paraderos' => [
                ['nombre' => 'Mercado Avelino', 'latitud' => -16.4250, 'longitud' => -71.5300, 'orden' => 1],
                ['nombre' => 'Av. Salaverry', 'latitud' => -16.4300, 'longitud' => -71.5220, 'orden' => 2],
                ['nombre' => 'TECSUP Arequipa', 'latitud' => -16.4419, 'longitud' => -71.5142, 'orden' => 3],
            ]],
            // Cotum B (ruta 2)
            ['ruta_nombre' => 'Cotum B', 'paraderos' => [
                ['nombre' => 'Plaza de Armas', 'latitud' => -16.3989, 'longitud' => -71.5370, 'orden' => 1],
                ['nombre' => 'Puente Grau', 'latitud' => -16.3900, 'longitud' => -71.5400, 'orden' => 2],
                ['nombre' => 'Cayma', 'latitud' => -16.3647, 'longitud' => -71.5575, 'orden' => 3],
            ]],
            // El Conquistador (ruta 3)
            ['ruta_nombre' => 'El Conquistador', 'paraderos' => [
                ['nombre' => 'Terminal Terrestre', 'latitud' => -16.4150, 'longitud' => -71.5500, 'orden' => 1],
                ['nombre' => 'Av. Ejército', 'latitud' => -16.4050, 'longitud' => -71.5450, 'orden' => 2],
                ['nombre' => 'Yanahuara', 'latitud' => -16.3892, 'longitud' => -71.5508, 'orden' => 3],
            ]],
            // San Martín (ruta 4)
            ['ruta_nombre' => 'San Martín', 'paraderos' => [
                ['nombre' => 'Mercado San Camilo', 'latitud' => -16.4027, 'longitud' => -71.5337, 'orden' => 1],
                ['nombre' => 'Av. Venezuela', 'latitud' => -16.4100, 'longitud' => -71.5250, 'orden' => 2],
                ['nombre' => 'Mall Aventura', 'latitud' => -16.4211, 'longitud' => -71.5298, 'orden' => 3],
            ]],
            // Arequipa - Sachaca (ruta 5)
            ['ruta_nombre' => 'Arequipa - Sachaca', 'paraderos' => [
                ['nombre' => 'Plaza de Armas', 'latitud' => -16.3989, 'longitud' => -71.5370, 'orden' => 1],
                ['nombre' => 'Av. La Marina', 'latitud' => -16.4100, 'longitud' => -71.5450, 'orden' => 2],
                ['nombre' => 'Mirador de Sachaca', 'latitud' => -16.4267, 'longitud' => -71.5591, 'orden' => 3],
            ]],
        ];

        foreach ($paraderos as $data) {
            $ruta = Ruta::where('nombre', $data['ruta_nombre'])->first();
            if ($ruta) {
                foreach ($data['paraderos'] as $p) {
                    Paradero::create([
                        'ruta_id' => $ruta->id,
                        'nombre' => $p['nombre'],
                        'latitud' => $p['latitud'],
                        'longitud' => $p['longitud'],
                        'orden' => $p['orden'],
                    ]);
                }
            }
        }
    }
}
