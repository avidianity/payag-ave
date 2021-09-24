<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * @test
     */
    public function it_should_log_a_user_in()
    {
        $data = ['email' => $this->faker->safeEmail, 'password' => $this->faker->password];

        User::factory()->create($data);

        $response = $this->post(route('auth.login', $data), ['Accept' => 'application/json']);

        $response->assertOk()->assertJsonStructure([
            'token', 'user'
        ]);
    }

    /**
     * @test
     */
    public function it_should_register_a_user()
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'password' => $this->faker->numberBetween(11111111111, 9999999999),
        ];

        $response = $this->post(route('auth.register', $data), ['Accept' => 'application/json']);

        $response->assertNoContent();
    }
}
