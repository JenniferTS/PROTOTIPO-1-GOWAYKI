<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserProfileFactory extends Factory
{
    protected $model = UserProfile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'bio' => fake()->optional(0.7)->sentence(8),
            'telefono' => fake()->optional(0.5)->phoneNumber(),
            'direccion' => fake()->optional(0.6)->address(),
            'fecha_nacimiento' => fake()->optional(0.6)->dateTimeBetween('-60 years', '-18 years'),
            'puntaje_exploracion' => fake()->numberBetween(0, 500),
        ];
    }
}
