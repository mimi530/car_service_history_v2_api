<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

class RepairFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3, false),
            'description' => $this->faker->sentence(10, false),
            'milage' => $this->faker->numberBetween(50000, 500000),
            'date' => $this->faker->date(),
            'car_id' => Car::factory(),
        ];
    }
}
