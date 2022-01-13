<?php

namespace Tests\Feature\Repair;

use App\Models\Car;
use App\Models\Repair;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotListRepairs()
    {
        $car = Car::factory()->create();
        $response = $this->getJson(
            route('cars.repairs.index', $car)
        );
        $response->assertStatus(401);
    }

    public function testUserCannotListSomeonesRepairs()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $otherUser->id]);
        $repairs = Repair::factory()->count(5)->create(['car_id' => $car->id]);
        $this->actingAs($user);
        $response = $this->getJson(
            route('cars.repairs.index', $car)
        );
        $response->assertStatus(403);
    }

    public function testUserCanListRepairs()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $user->id]);
        $repairs = Repair::factory()->count(5)->create(['car_id' => $car->id]);
        $this->actingAs($user2);
        $response = $this->getJson(
            route('cars.repairs.index', $car)
        )->dump();
        $response->assertStatus(200)->assertJsonCount(5, 'repairs');
    }
}
