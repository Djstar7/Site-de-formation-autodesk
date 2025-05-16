<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Le modèle correspondant à cette factory.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Définition des valeurs par défaut pour le modèle.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // change si besoin
            'remember_token' => Str::random(10),
        ];
    }
}
