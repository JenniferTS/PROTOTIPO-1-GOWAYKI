<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin GoWayki',
            'email' => 'admin@gowayki.com',
            'password' => Hash::make('GoWayki2025!'),
            'role' => 'admin',
            'notificaciones_activas' => true,
        ]);
    }
}
