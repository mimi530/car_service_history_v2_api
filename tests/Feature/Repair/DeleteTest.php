<?php

namespace Tests\Feature\Repair;

use App\Models\Car;
use App\Models\Repair;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotDeleteRepair()
    {
        $car = Car::factory()->create();
        $repair = Repair::factory()->create(['car_id' => $car->id]);
        $response = $this->deleteJson(
            route('cars.repairs.destroy', ['car' => $car, 'repair' => $repair])
        );
        $response->assertStatus(401);
        $this->assertDatabaseHas('repairs', $repair->getAttributes());
    }

    public function testUserCannotDeleteSomeonesRepair()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $otherUser->id]);
        $repair = Repair::factory()->create(['car_id' => $car->id]);
        $this->actingAs($user);
        $response = $this->deleteJson(
            route('cars.repairs.destroy', ['car' => $car, 'repair' => $repair])
        );
        $response->assertStatus(403);
        $this->assertDatabaseHas('repairs', $repair->getAttributes());
    }

    public function testUserCanDeleteHisRepair()
    {
        $user = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $user->id]);
        $repair = Repair::factory()->create(['car_id' => $car->id]);
        $this->actingAs($user);
        $response = $this->deleteJson(
            route('cars.repairs.destroy', ['car' => $car, 'repair' => $repair])
        );
        $response->assertStatus(200);
        $this->assertDatabaseMissing('repairs', $repair->getAttributes());
    }
}
