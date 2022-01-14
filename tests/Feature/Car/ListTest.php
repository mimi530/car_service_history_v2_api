<?php

namespace Tests\Feature\Car;

use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ListTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotListCars()
    {
        $response = $this->getJson(
            route('cars.index')
        );
        $response->assertStatus(401);
    }

    public function testUserCannotListSomeonesCars()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $cars = Car::factory()->count(5)->create(['user_id' => $otherUser->id]);
        Sanctum::actingAs($user);
        $response = $this->getJson(
            route('cars.index')
        );
        $response->assertStatus(200)->assertJsonCount(0, 'cars');
    }

    public function testUserCanListCars()
    {
        $user = User::factory()->create();
        $car = Car::factory()->count(5)->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);
        $response = $this->getJson(
            route('cars.index')
        );
        $response->assertStatus(200)->assertJsonCount(5, 'cars');
    }
}
