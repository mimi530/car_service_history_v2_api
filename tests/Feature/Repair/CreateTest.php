<?php

namespace Tests\Feature\Repair;

use App\Models\Car;
use App\Models\Repair;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotAddRepair()
    {
        $car = Car::factory()->create();
        $response = $this->postJson(
            route('cars.repairs.store', $car)
        );
        $response->assertStatus(401);
    }

    public function testUserCannotAddRepairToSomeonesRepair()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $otherUser->id]);
        $repair = Repair::factory()->make(['car_id' => $car->id]);
        $this->actingAs($user);
        $response = $this->postJson(
            route('cars.repairs.store', $car), $repair->toArray()
        );
        $response->assertStatus(403);
        $this->assertDatabaseMissing('cars', $repair->getAttributes());
    }

    public function testUserCannotAddRepairWithInvalidData()
    {
        $user = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);
        $invalidRepair = [
            "title" => '',
            "milage" => 'test',
            "date" => '01-01-2022'
        ];
        $response = $this->postJson(
            route('cars.repairs.store', $car), $invalidRepair
        );
        $response->assertStatus(422)->assertJsonCount(3, 'errors');
        $this->assertDatabaseMissing('cars', $invalidRepair);
    }

    public function testUserCanAddRepairWithValidData()
    {
        $user = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $user->id]);
        $repair = Repair::factory()->make(['car_id' => $car->id]);
        $this->actingAs($user);
        $response = $this->postJson(
            route('cars.repairs.store', $car), $repair->toArray()
        );
        $response->assertStatus(201);
        $this->assertDatabaseHas('repairs', $repair->getAttributes());
    }
}
