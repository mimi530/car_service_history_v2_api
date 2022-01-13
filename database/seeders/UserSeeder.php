<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'mimi0192@wp.pl',
            'password' => bcrypt('password'),
            'name' => 'Michał Domżalski'
        ]);
    }
}
