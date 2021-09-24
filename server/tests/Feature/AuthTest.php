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
    public function it_should_lock_a_user()
    {
        $data = ['email' => $this->faker->safeEmail, 'password' => $this->faker->password];

        $user = User::factory()->create($data);

        $user->resetLock();

        $data['password'] = 'something else';

        for ($x = 0; $x < config('auth.blocking.retries'); $x++) {
            $this->post(route('auth.login', $data), ['Accept' => 'application/json']);
        }

        $response = $this->post(route('auth.login', $data), ['Accept' => 'application/json']);

        $response->assertStatus(429);
    }

    /**
     * @test
     */
    public function it_should_prevent_a_locked_user_from_logging_in()
    {
        $data = ['email' => $this->faker->safeEmail, 'password' => $this->faker->password];

        $user = User::factory()->create($data);

        $minutes = config('auth.blocking.minutes');
        $seconds = config('auth.blocking.seconds');

        $user->blocked_until = now()
            ->addSeconds($minutes)
            ->addMinutes($seconds);

        $user->save();

        $response = $this->post(route('auth.login', $data), ['Accept' => 'application/json']);

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function it_should_let_an_expired_locked_user_log_in()
    {
        $data = ['email' => $this->faker->safeEmail, 'password' => $this->faker->password];

        $user = User::factory()->create($data);

        $minutes = config('auth.blocking.minutes');
        $seconds = config('auth.blocking.seconds');

        $user->blocked_until = now()
            ->addSeconds($minutes)
            ->addMinutes($seconds);

        $user->save();

        $this->travel(1)->hours();

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
            'phone' => $this->faker->numberBetween(11111111111, 99999999999),
            'password' => $this->faker->password,
        ];

        $response = $this->post(route('auth.register', $data), ['Accept' => 'application/json']);

        $response->assertNoContent();
    }
}
