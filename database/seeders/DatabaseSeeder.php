<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Repair;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Repair::factory(10)->create();
    }
}
