<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlansModel>
 */
class PlansModelFactory extends Factory
{
    protected $model = \App\Models\PlansModel::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title_plans' => $this->faker->sentence(),
            'description_plans' => $this->faker->paragraph(),
            'price_plans' => $this->faker->randomFloat(2, 10, 100),
            'image_plans' => $this->faker->imageUrl(),
            'file_plans' => $this->faker->filePath(),

        ];
    }
}
