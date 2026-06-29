<?php

namespace Database\Seeders;

use App\Models\Destino;
use App\Models\LugarVisitado;
use App\Models\Paradero;
use App\Models\Recorrido;
use App\Models\Ruta;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RutaSeeder::class,
            ParaderoSeeder::class,
            DestinoSeeder::class,
            UserSeeder::class,
        ]);

        $users = User::factory(50)->create();
        $rutas = Ruta::factory(20)->create();
        $destinos = Destino::factory(30)->create();

        foreach ($rutas as $ruta) {
            $num = fake()->numberBetween(3, 8);
            for ($i = 1; $i <= $num; $i++) {
                Paradero::factory()->paraRuta($ruta->id, $i)->create();
            }
        }

        Recorrido::factory(100)->recycle($users)->recycle($rutas)->create();

        $activeDestinos = Destino::where('activo', true)->get();
        foreach ($users as $user) {
            $visitados = $activeDestinos->random(fake()->numberBetween(0, min(5, $activeDestinos->count())));
            foreach ($visitados as $destino) {
                LugarVisitado::factory()->create([
                    'user_id' => $user->id,
                    'destino_id' => $destino->id,
                ]);
            }
        }

        foreach ($users as $user) {
            UserProfile::factory()->create(['user_id' => $user->id]);
        }

        UserProfile::factory()->create(['user_id' => 1]);
    }
}
