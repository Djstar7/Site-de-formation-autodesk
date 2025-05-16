<?php

namespace Database\Factories;

use App\Models\UsersModel;
use App\Models\UsersoneModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UsersoneModel>
 */
class UsersoneModelFactory extends Factory
{
    protected $model = UsersoneModel::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_usersone' => $this->faker->name(),
            'email_usersone' => $this->faker->unique()->safeEmail(),
            'password_usersone' => bcrypt('password'), // password
        ];
    }
}
