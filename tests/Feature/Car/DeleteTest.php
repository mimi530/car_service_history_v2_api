<?php

namespace Tests\Feature\Car;

use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotDeleteCar()
    {
        $car = Car::factory()->create();
        $response = $this->deleteJson(
            route('cars.destroy', $car)
        );
        $response->assertStatus(401);
        $this->assertDatabaseHas('cars', $car->getAttributes());
    }

    public function testUserCannotDeleteSomeonesCar()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $otherUser->id]);
        $this->actingAs($user);
        $response = $this->deleteJson(
            route('cars.destroy', $car)
        );
        $response->assertStatus(403);
        $this->assertDatabaseHas('cars', $car->getAttributes());
    }

    public function testUserCanDeleteHisCar()
    {
        $user = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);
        $response = $this->deleteJson(
            route('cars.destroy', $car)
        );
        $response->assertStatus(200);
        $this->assertDatabaseMissing('cars', $car->getAttributes());
    }
}
