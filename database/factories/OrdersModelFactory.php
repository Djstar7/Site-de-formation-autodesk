<?php

namespace Database\Factories;

use App\Models\OrdersModel;
use App\Models\PlansModel;
use App\Models\TrainingsModel;
use App\Models\UsersoneModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrdersModel>
 */
class OrdersModelFactory extends Factory
{
    protected $model = OrdersModel::class;
    /**
     * The name of the factory's corresponding model.
     *
     *
     */
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => UsersoneModel::factory(),
            'plan_id' =>PlansModel::factory(),
            'training_id' => TrainingsModel::factory(),
            'status_orders' => $this->faker->randomElement(['pending', 'completed', 'canceled']),
            'total_price_orders' => $this->faker->randomFloat(2, 10, 100),
        ];
    }
}
