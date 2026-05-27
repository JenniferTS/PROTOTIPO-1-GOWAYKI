<?php

namespace Database\Seeders;

use App\Models\Destino;
use Illuminate\Database\Seeder;

class DestinoSeeder extends Seeder
{
    public function run(): void
    {
        $destinos = [
            [
                'nombre' => 'Plaza de Armas de Cayma',
                'tagline' => 'Historia viva entre sillar y tradición',
                'descripcion' => 'La Plaza de Armas del distrito de Cayma es uno de los espacios públicos más emblemáticos de Arequipa. Ubicada a 2,380 metros sobre el nivel del mar, ofrece una vista panorámica privilegiada del volcán Misti y el valle del río Chili. El distrito de Cayma tiene raíces preincaicas y coloniales; su iglesia San Miguel Arcángel, declarada Patrimonio Cultural de la Nación, data del siglo XVII y es el punto focal de la plaza. El espacio cuenta con jardines ornamentales, fuente central, bancas de sillar blanco y comercio local de artesanías y gastronomía típica arequipeña. Es punto de partida ideal para rutas hacia la Campiña de Arequipa.',
                'categoria' => 'historico',
                'distrito' => 'Cayma',
                'latitud' => -16.3647,
                'longitud' => -71.5575,
                'imagen_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8f/Cayma_plaza.jpg/1280px-Cayma_plaza.jpg',
                'calificacion' => 4.70,
            ],
            [
                'nombre' => 'Plaza de Armas de Arequipa',
                'tagline' => 'El corazón de la Ciudad Blanca',
                'descripcion' => 'La Plaza de Armas de Arequipa es el corazón de la ciudad, rodeada por edificios coloniales de sillar blanco, incluyendo la Catedral y la Iglesia de la Compañía. Es uno de los espacios públicos más importantes del Perú.',
                'categoria' => 'historico',
                'distrito' => 'Arequipa',
                'latitud' => -16.3989,
                'longitud' => -71.5370,
                'imagen_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6e/Plaza_de_Armas_Arequipa.jpg/1280px-Plaza_de_Armas_Arequipa.jpg',
                'calificacion' => 4.80,
            ],
            [
                'nombre' => 'Monasterio de Santa Catalina',
                'tagline' => 'Un mundo dentro de la ciudad',
                'descripcion' => 'El Monasterio de Santa Catalina es un impresionante complejo religioso del siglo XVI, una ciudad dentro de la ciudad. Sus calles pintorescas, claustros y jardines lo convierten en uno de los atractivos más visitados de Arequipa.',
                'categoria' => 'cultural',
                'distrito' => 'Arequipa',
                'latitud' => -16.3960,
                'longitud' => -71.5369,
                'imagen_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/Monasterio_de_Santa_Catalina_en_Arequipa.jpg/1280px-Monasterio_de_Santa_Catalina_en_Arequipa.jpg',
                'calificacion' => 4.70,
            ],
            [
                'nombre' => 'Mirador de Sachaca',
                'tagline' => 'Arequipa desde las alturas',
                'descripcion' => 'El Mirador de Sachaca ofrece una vista espectacular del valle del río Chili y los volcanes Misti y Chachani. Es un lugar ideal para disfrutar del atardecer arequipeño.',
                'categoria' => 'turistico',
                'distrito' => 'Sachaca',
                'latitud' => -16.4267,
                'longitud' => -71.5591,
                'imagen_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/26/Mirador_de_Sachaca.jpg/1280px-Mirador_de_Sachaca.jpg',
                'calificacion' => 4.30,
            ],
            [
                'nombre' => 'Mercado San Camilo',
                'tagline' => 'El sabor auténtico de Arequipa',
                'descripcion' => 'El Mercado San Camilo es el mercado tradicional más importante de Arequipa. Ofrece una gran variedad de productos frescos, comidas típicas, jugos naturales y artesanías. Es el lugar perfecto para degustar la gastronomía arequipeña auténtica.',
                'categoria' => 'gastronomico',
                'distrito' => 'Arequipa',
                'latitud' => -16.4027,
                'longitud' => -71.5337,
                'imagen_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Mercado_San_Camilo.jpg/1280px-Mercado_San_Camilo.jpg',
                'calificacion' => 4.40,
            ],
            [
                'nombre' => 'Mirador de Yanahuara',
                'tagline' => 'La mejor vista del Misti',
                'descripcion' => 'El Mirador de Yanahuara es famoso por sus arcos de sillar blanco y su vista panorámica de Arequipa y el volcán Misti. Es uno de los lugares más fotografiados de la ciudad.',
                'categoria' => 'turistico',
                'distrito' => 'Yanahuara',
                'latitud' => -16.3892,
                'longitud' => -71.5508,
                'imagen_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b1/Arequipa_desde_Yanahuara.jpg/1280px-Arequipa_desde_Yanahuara.jpg',
                'calificacion' => 4.60,
            ],
            [
                'nombre' => 'Mall Aventura Arequipa',
                'tagline' => 'Diversión y compras en un solo lugar',
                'descripcion' => 'El Mall Aventura Arequipa es el centro comercial más grande de la ciudad, con tiendas, restaurantes, cines y zonas de entretenimiento para toda la familia.',
                'categoria' => 'recreativo',
                'distrito' => 'Arequipa',
                'latitud' => -16.4211,
                'longitud' => -71.5298,
                'imagen_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/cb/Mall_Aventura_Arequipa.jpg/1280px-Mall_Aventura_Arequipa.jpg',
                'calificacion' => 4.20,
            ],
            [
                'nombre' => 'TECSUP Arequipa',
                'tagline' => 'Tecnología e innovación en Arequipa',
                'descripcion' => 'TECSUP Arequipa es una institución educativa de formación tecnológica, ubicada en el distrito de José Luis Bustamante y Rivero. Es un punto de referencia en la ciudad.',
                'categoria' => 'cultural',
                'distrito' => 'José Luis Bustamante y Rivero',
                'latitud' => -16.4419,
                'longitud' => -71.5142,
                'imagen_url' => null,
                'calificacion' => 4.00,
            ],
        ];

        foreach ($destinos as $destino) {
            Destino::create($destino);
        }
    }
}
