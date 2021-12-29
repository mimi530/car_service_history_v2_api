<?php

namespace Tests\Unit\Models;

use App\Models\Car;
use App\Models\Repair;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RepairTest extends TestCase
{
    use RefreshDatabase;

    public function testItBelongsToCar()
    {
        $car = Car::factory()->create();
        $repair = Repair::factory()->create(['car_id' => $car->id]);
        $this->assertInstanceOf(Car::class, $repair->car);
    }
}
