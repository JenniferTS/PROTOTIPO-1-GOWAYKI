<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParaderoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('paraderos')->insert([
            // Canarios S.A. - Mercado Avelino a TECSUP Arequipa
            ['ruta_id' => 1, 'nombre' => 'Mercado Avelino', 'latitud' => -16.4250, 'longitud' => -71.5300, 'orden' => 1, 'imagen_url' => 'https://images.unsplash.com/photo-1533900298318-6b8da08a523e?auto=format&fit=crop&w=1600&q=80', 'created_at' => now(), 'updated_at' => now()],
            ['ruta_id' => 1, 'nombre' => 'Av. Goyeneche', 'latitud' => -16.4098, 'longitud' => -71.5340, 'orden' => 2, 'imagen_url' => 'https://commons.wikimedia.org/wiki/Special:Redirect/file/Hospital%20Goyeneche%20de%20Arequipa.JPG?width=1600', 'created_at' => now(), 'updated_at' => now()],
            ['ruta_id' => 1, 'nombre' => 'Plaza de Armas', 'latitud' => -16.3989, 'longitud' => -71.5370, 'orden' => 3, 'imagen_url' => 'https://commons.wikimedia.org/wiki/Special:Redirect/file/Plaza%20de%20Arequipa.jpg?width=1600', 'created_at' => now(), 'updated_at' => now()],
            ['ruta_id' => 1, 'nombre' => 'Puente Grau', 'latitud' => -16.3900, 'longitud' => -71.5400, 'orden' => 4, 'imagen_url' => 'https://commons.wikimedia.org/wiki/Special:Redirect/file/Miguel%20Grau%20Statue%20Arequipa%20Puente%20Grau.jpg?width=1600', 'created_at' => now(), 'updated_at' => now()],
            ['ruta_id' => 1, 'nombre' => 'Av. Ejército - San Lázaro', 'latitud' => -16.3958, 'longitud' => -71.5425, 'orden' => 5, 'imagen_url' => 'https://commons.wikimedia.org/wiki/Special:Redirect/file/Av.%20Ejercito.JPG?width=1600', 'created_at' => now(), 'updated_at' => now()],
            ['ruta_id' => 1, 'nombre' => 'Óvalo Lambramani', 'latitud' => -16.4190, 'longitud' => -71.5255, 'orden' => 6, 'imagen_url' => 'https://images.unsplash.com/photo-1518005020951-eccb494ad742?auto=format&fit=crop&w=1600&q=80', 'created_at' => now(), 'updated_at' => now()],
            ['ruta_id' => 1, 'nombre' => 'Av. Dolores', 'latitud' => -16.4235, 'longitud' => -71.5175, 'orden' => 7, 'imagen_url' => 'https://images.unsplash.com/photo-1494522855154-9297ac14b55f?auto=format&fit=crop&w=1600&q=80', 'created_at' => now(), 'updated_at' => now()],
            ['ruta_id' => 1, 'nombre' => 'Hospital Honorio Delgado', 'latitud' => -16.4140, 'longitud' => -71.5220, 'orden' => 8, 'imagen_url' => 'https://commons.wikimedia.org/wiki/Special:Redirect/file/Hospital%20General%20de%20Arequipa.JPG?width=1600', 'created_at' => now(), 'updated_at' => now()],
            ['ruta_id' => 1, 'nombre' => 'Av. Independencia - Cruce', 'latitud' => -16.4060, 'longitud' => -71.5270, 'orden' => 9, 'imagen_url' => 'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=1600&q=80', 'created_at' => now(), 'updated_at' => now()],
            ['ruta_id' => 1, 'nombre' => 'Real Plaza Cayma', 'latitud' => -16.3928, 'longitud' => -71.5488, 'orden' => 10, 'imagen_url' => 'https://images.unsplash.com/photo-1519567241046-7f570eee3ce6?auto=format&fit=crop&w=1600&q=80', 'created_at' => now(), 'updated_at' => now()],
            ['ruta_id' => 1, 'nombre' => 'Av. Aviación', 'latitud' => -16.3770, 'longitud' => -71.5565, 'orden' => 11, 'imagen_url' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1600&q=80', 'created_at' => now(), 'updated_at' => now()],
            ['ruta_id' => 1, 'nombre' => 'Urb. La Estación', 'latitud' => -16.4090, 'longitud' => -71.5190, 'orden' => 12, 'imagen_url' => 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?auto=format&fit=crop&w=1600&q=80', 'created_at' => now(), 'updated_at' => now()],
            ['ruta_id' => 1, 'nombre' => 'TECSUP Arequipa', 'latitud' => -16.4419, 'longitud' => -71.5142, 'orden' => 13, 'imagen_url' => 'https://www.tecsup.edu.pe/wp-content/uploads/2024/07/WEB_SEDE-SUR-1.jpg', 'created_at' => now(), 'updated_at' => now()],

            // Cotum B
            ['ruta_id' => 2, 'nombre' => 'Plaza de Armas', 'latitud' => -16.3989, 'longitud' => -71.5370, 'orden' => 1, 'imagen_url' => 'https://commons.wikimedia.org/wiki/Special:Redirect/file/Plaza%20de%20Arequipa.jpg?width=1600', 'created_at' => now(), 'updated_at' => now()],
            ['ruta_id' => 2, 'nombre' => 'Puente Grau', 'latitud' => -16.3900, 'longitud' => -71.5400, 'orden' => 2, 'imagen_url' => 'https://commons.wikimedia.org/wiki/Special:Redirect/file/Miguel%20Grau%20Statue%20Arequipa%20Puente%20Grau.jpg?width=1600', 'created_at' => now(), 'updated_at' => now()],
            ['ruta_id' => 2, 'nombre' => 'Cayma', 'latitud' => -16.3647, 'longitud' => -71.5575, 'orden' => 3, 'imagen_url' => 'https://commons.wikimedia.org/wiki/Special:Redirect/file/Plaza%20mayor%20de%20Cayma%20%28Plaza%20de%20Armas%29.jpg?width=1600', 'created_at' => now(), 'updated_at' => now()],

            // Ruta 3
            ['ruta_id' => 3, 'nombre' => 'Terminal Terrestre', 'latitud' => -16.4150, 'longitud' => -71.5500, 'orden' => 1, 'imagen_url' => 'https://images.unsplash.com/photo-1494522855154-9297ac14b55f?auto=format&fit=crop&w=1600&q=80', 'created_at' => now(), 'updated_at' => now()],
            ['ruta_id' => 3, 'nombre' => 'Av. Ejército', 'latitud' => -16.4050, 'longitud' => -71.5450, 'orden' => 2, 'imagen_url' => 'https://commons.wikimedia.org/wiki/Special:Redirect/file/Av.%20Ejercito.JPG?width=1600', 'created_at' => now(), 'updated_at' => now()],
            ['ruta_id' => 3, 'nombre' => 'Yanahuara', 'latitud' => -16.3892, 'longitud' => -71.5508, 'orden' => 3, 'imagen_url' => 'https://commons.wikimedia.org/wiki/Special:Redirect/file/Vista%20desde%20el%20mirador%20de%20Yanahuara%20en%20Arequipa.jpg?width=1600', 'created_at' => now(), 'updated_at' => now()],

            // Ruta 4
            ['ruta_id' => 4, 'nombre' => 'Mercado San Camilo', 'latitud' => -16.4027, 'longitud' => -71.5337, 'orden' => 1, 'imagen_url' => 'https://commons.wikimedia.org/wiki/Special:Redirect/file/Mercado%20de%20San%20Camilo%2001.jpg?width=1600', 'created_at' => now(), 'updated_at' => now()],
            ['ruta_id' => 4, 'nombre' => 'Av. Venezuela', 'latitud' => -16.4100, 'longitud' => -71.5250, 'orden' => 2, 'imagen_url' => 'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=1600&q=80', 'created_at' => now(), 'updated_at' => now()],
            ['ruta_id' => 4, 'nombre' => 'Mall Aventura', 'latitud' => -16.4211, 'longitud' => -71.5298, 'orden' => 3, 'imagen_url' => 'https://images.unsplash.com/photo-1519567241046-7f570eee3ce6?auto=format&fit=crop&w=1600&q=80', 'created_at' => now(), 'updated_at' => now()],

            // Ruta 5
            ['ruta_id' => 5, 'nombre' => 'Plaza de Armas', 'latitud' => -16.3989, 'longitud' => -71.5370, 'orden' => 1, 'imagen_url' => 'https://commons.wikimedia.org/wiki/Special:Redirect/file/Plaza%20de%20Arequipa.jpg?width=1600', 'created_at' => now(), 'updated_at' => now()],
            ['ruta_id' => 5, 'nombre' => 'Av. La Marina', 'latitud' => -16.4100, 'longitud' => -71.5450, 'orden' => 2, 'imagen_url' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1600&q=80', 'created_at' => now(), 'updated_at' => now()],
            ['ruta_id' => 5, 'nombre' => 'Mirador de Sachaca', 'latitud' => -16.4267, 'longitud' => -71.5591, 'orden' => 3, 'imagen_url' => 'https://commons.wikimedia.org/wiki/Special:Redirect/file/Mirador%20de%20Sachaca.jpg?width=1600', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
