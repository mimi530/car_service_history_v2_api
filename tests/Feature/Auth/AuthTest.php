<?php

namespace Tests\Feature\Auth;

use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanRegisterWithValidData()
    {
        $data = [
            'name' => 'Test Test',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];
        $response = $this->postJson(
            route('auth.register'), $data
        );
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', Arr::except($data, ['password', 'password_confirmation']));
    }

    public function testUserCannotRegisterWithInvalidData()
    {
        $data = [
            'name' => '',
            'email' => 'test@.@test.com',
            'password' => 'pass',
            'password_confirmation' => 'password'
        ];
        $response = $this->postJson(
            route('auth.register'), $data
        );
        $response->assertStatus(422)->assertJsonCount(3, 'errors');
        $this->assertDatabaseMissing('users', Arr::except($data, ['password', 'password_confirmation']));
    }

    public function testUserCanLoginWithValidCredentials()
    {
        $credentials = [
            'email' => 'test@test.com',
            'password' => 'password',
        ];
        User::factory()->create([
            'email' => 'test@test.com'
        ]);
        $response = $this->postJson(
            route('auth.login'), $credentials
        );
        $response->assertStatus(200);
    }

    public function testUserCannotLoginWithInvalidCredentials()
    {
        $credentials = [
            'email' => 'test@test.com',
            'password' => 'passwd',
        ];
        User::factory()->create([
            'email' => 'test@test.com'
        ]);
        $response = $this->postJson(
            route('auth.login'), $credentials
        );
        $response->assertStatus(401);
    }

    public function testUserCanLoginAndThenLogout()
    {
        $credentials = [
            'email' => 'test@test.com',
            'password' => 'password',
        ];
        User::factory()->create([
            'email' => 'test@test.com'
        ]);
        $response = $this->postJson(
            route('auth.login'), $credentials
        );
        $response->assertStatus(200);
        $response = $this->postJson(
            route('auth.logout'),
        );
        $response->assertStatus(200);
    }
}
