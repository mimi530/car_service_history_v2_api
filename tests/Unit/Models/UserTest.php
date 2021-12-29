<?php

namespace Tests\Unit\Models;

use App\Models\Car;
use App\Models\Repair;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testItHasManyRepairs()
    {
        $user = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $user->id]);
        $repairs = Repair::factory()->count(5)->create(['car_id' => $car->id]);

        $car->repairs()->saveMany($repairs);
        $this->assertInstanceOf(Collection::class, $user->repairs);
        $this->assertCount(5, $user->repairs);
    }

    public function testItHasManyCars()
    {
        $user = User::factory()->create();
        $cars = Car::factory()->count(5)->create(['user_id' => $user->id]);

        $user->cars()->saveMany($cars);
        $this->assertInstanceOf(Collection::class, $user->cars);
        $this->assertCount(5, $user->cars);
    }
}
