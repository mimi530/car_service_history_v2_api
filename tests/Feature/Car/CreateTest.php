<?php

namespace Tests\Feature\Car;

use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotAddCar()
    {
        $response = $this->postJson(
            route('cars.store')
        );
        $response->assertStatus(401);
    }

    public function testUserCannotAddCarWithInvalidData()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $invalidCar = [
            "name" => '',
            "milage" => false,
        ];
        $response = $this->postJson(
            route('cars.store'), $invalidCar
        );
        $response->assertStatus(422)->assertJsonCount(2, 'errors');
        $this->assertDatabaseMissing('cars', $invalidCar);
    }

    public function testUserCanAddCarWithValidData()
    {
        $user = User::factory()->create();
        $car = Car::factory()->make(['user_id' => $user->id]);
        $this->actingAs($user);
        $response = $this->postJson(
            route('cars.store'), $car->toArray()
        );
        $response->assertStatus(201);
        $this->assertDatabaseHas('cars', $car->getAttributes());
    }
}
