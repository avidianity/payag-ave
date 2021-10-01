<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
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

        $this->post(route('v1.auth.login', $data), ['Accept' => 'application/json'])->assertOk()
            ->assertJsonStructure([
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
            $this->post(route('v1.auth.login', $data), ['Accept' => 'application/json']);
        }

        $this->post(route('v1.auth.login', $data), ['Accept' => 'application/json'])
            ->assertStatus(429);
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

        $this->post(route('v1.auth.login', $data), ['Accept' => 'application/json'])
            ->assertStatus(403);
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

        $this->post(route('v1.auth.login', $data), ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonStructure([
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

        Notification::fake();

        $this->post(route('v1.auth.register', $data), ['Accept' => 'application/json'])
            ->assertNoContent();

        Notification::assertSentTo(User::firstOrFail(), VerifyEmail::class);
    }

    /**
     * @test
     */
    public function it_should_send_a_password_reset_email()
    {
        $user = User::factory()->create();

        Notification::fake();

        $this->post(
            route('v1.auth.password.email'),
            ['email' => $user->email],
            [
                'Accept' => 'application/json',
            ]
        )
            ->assertJson([
                'status' => __(Password::RESET_LINK_SENT)
            ])
            ->assertOk();

        Notification::assertSentTo($user, ResetPassword::class);
    }

    /**
     * @test
     */
    public function it_should_reset_password()
    {
        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create();

        $config = config('auth.passwords.users');

        $key = config('app.key');

        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        $connection = $config['connection'] ?? null;

        $tokens = new DatabaseTokenRepository(
            $this->app['db']->connection($connection),
            $this->app['hash'],
            $config['table'],
            $key,
            $config['expire'],
            $config['throttle'] ?? 0
        );

        $token = $tokens->create($user);

        $user->sendPasswordResetNotification($token);

        $password = $this->faker->password;

        $this->post(
            route('v1.auth.password.update'),
            [
                'token' => $token,
                'email' => $user->email,
                'password' => $password,
                'password_confirmation' => $password,
            ],
            [
                'Accept' => 'application/json',
            ]
        )
            ->assertJson([
                'status' => __(Password::PASSWORD_RESET),
            ])
            ->assertOk();
    }
}
