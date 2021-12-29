<?php

namespace Tests\Feature\Repair;

use App\Models\Car;
use App\Models\Repair;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotUpdateRepair()
    {
        $car = Car::factory()->create();
        $repair = Repair::factory()->create(['car_id' => $car->id]);
        $response = $this->putJson(
            route('cars.repairs.update', ['car' => $car, 'repair' => $repair]), [
                'title' => "Nowa nazwa",
                'milage' => 123123,
                'date' => "2021-12-31"
            ]
        );
        $response->assertStatus(401);
        $this->assertDatabaseHas('repairs', Repair::find($repair->id)->getAttributes());
    }

    public function testUserCannotUpdateSomeonesRepair()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $otherUser->id]);
        $repair = Repair::factory()->create(['car_id' => $car->id]);
        $this->actingAs($user);
        $response = $this->putJson(
            route('cars.repairs.update', compact('car', 'repair')), [
                'title' => "Nowa nazwa",
                'milage' => 123123,
                'date' => "2021-12-31"
            ]
        );
        $response->assertStatus(403);
        $this->assertDatabaseHas('repairs', Repair::find($repair->id)->getAttributes());
    }

    public function testUserCannotUpdateHisRepairWithInvalidData()
    {
        $user = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $user->id]);
        $repair = Repair::factory()->create(['car_id' => $car->id]);
        $this->actingAs($user);
        $response = $this->putJson(
            route('cars.repairs.update', ['car' => $car, 'repair' => $repair]), [
                'title' => "",
                'milage' => 'test',
                'date' => "01-01-2022"
            ]
        );
        $response->assertStatus(422)->assertJsonCount(3, 'errors');
        $this->assertDatabaseHas('repairs', Repair::find($repair->id)->getAttributes());
    }

    public function testUserCanUpdateHisRepair()
    {
        $user = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $user->id]);
        $repair = Repair::factory()->create(['car_id' => $car->id]);
        $this->actingAs($user);
        $response = $this->putJson(
            route('cars.repairs.update', ['car' => $car, 'repair' => $repair]), [
                'title' => "Nowa nazwa",
                'milage' => 123123,
                'date' => "2021-12-31"
            ]
        );
        $response->assertStatus(200);
        $this->assertDatabaseMissing('repairs', $repair->getAttributes());
        $this->assertDatabaseHas('repairs', Repair::find($repair->id)->getAttributes());
    }
}
