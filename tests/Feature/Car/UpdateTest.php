<?php

namespace Tests\Feature\Car;

use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotUpdateCar()
    {
        $car = Car::factory()->create();
        $response = $this->putJson(
            route('cars.update', $car), [
                'name' => "Nowa nazwa"
            ]
        );
        $response->assertStatus(401);
        $this->assertDatabaseHas('cars', $car->getAttributes());
    }

    public function testUserCannotUpdateSomeonesCar()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $otherUser->id]);
        $this->actingAs($user);
        $response = $this->putJson(
            route('cars.update', $car), [
                'name' => "Nowa nazwa"
            ]
        );
        $response->assertStatus(403);
        $this->assertDatabaseHas('cars', $car->getAttributes());
    }

    public function testUserCannotUpdateHisCarWithInvalidData()
    {
        $user = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);
        $response = $this->putJson(
            route('cars.update', $car), [
                'name' => "",
                'milage' => 'test'
            ]
        );
        $response->assertStatus(422)->assertJsonCount(2, 'errors');
        $this->assertDatabaseHas('cars', Car::find($car->id)->getAttributes());
    }

    public function testUserCanUpdateHisCar()
    {
        $user = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);
        $response = $this->putJson(
            route('cars.update', $car), [
                'name' => "Nowa nazwa"
            ]
        );
        $response->assertStatus(200);
        $this->assertDatabaseMissing('cars', $car->getAttributes());
        $this->assertDatabaseHas('cars', Car::find($car->id)->getAttributes());
    }
}
