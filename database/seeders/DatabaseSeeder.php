<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Repair;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)
            ->create()
            ->each(
            function ($user) {
                Car::factory(3)
                    ->create(['user_id' => $user->id])
                    ->each(
                        function ($car) {
                            Repair::factory(5)->create(['car_id' => $car->id]);
                        }
                    );
                }
            );
    }
}
