<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrainingsModel>
 */
class TrainingsModelFactory extends Factory
{
    protected $model = \App\Models\TrainingsModel::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title_trainings' => $this->faker->sentence(),
            'description_trainings' => $this->faker->paragraph(),
            'software_trainings' => $this->faker->randomElement(['AutaCAD', 'Revit', 'ArciCAD']),
            'price_trainings' => $this->faker->randomFloat(2, 10, 100),
            'video_trainings' => $this->faker->url(),
        ];
    }
}
