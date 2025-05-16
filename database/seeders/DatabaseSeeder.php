<?php

namespace Database\Seeders;

use App\Models\OrdersModel;
use App\Models\PlansModel;
use App\Models\TrainingsModel;
use App\Models\UsersoneModel;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);


        UsersoneModel::factory(10)->create();

        // \App\Models\PlansModel::factory(10)->create([
        PlansModel::factory(10)->create();

        TrainingsModel::factory(10)->create();
    }
}
