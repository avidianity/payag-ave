<?php

namespace Tests\Feature;

use App\Models\ChangeEmailRequest;
use App\Models\User;
use App\Notifications\RegisteredSMS;
use App\Notifications\ReVerifyEmail;
use App\Notifications\VerifyEmail;
use Avidian\Semaphore\Client;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Mockery;
use PHPUnit\Framework\Error\Notice;
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
            'phone' => '09' . $this->faker->numberBetween(111111111, 999999999),
            'password' => $this->faker->password,
        ];

        /**
         * @var \Mockery\MockInterface|\Mockery\LegacyMockInterface
         */
        $client = Mockery::mock(Client::class);

        $response = [
            [
                "mssage_id" => 1234567,
                "user_id" => 99556,
                "user" => "user@your.org",
                "account_id" => 90290,
                "account" => "Your Account Name",
                "recipient" => $data['phone'],
                "message" => "The message you sent",
                "sender_name" => "SEMAPHORE",
                "network" => "Globe",
                "status" => "Queued",
                "type" => "Single",
                "source" => "Api",
                "created_at" => "2016-01-01 00:01:01",
                "updated_at" => "2016-01-01 00:01:01",
            ]
        ];

        $client->shouldReceive('send')
            ->once()
            ->andReturn($response);

        $this->app->instance(Client::class, $client);

        Notification::fake();

        $this->post(route('v1.auth.register', $data), ['Accept' => 'application/json'])
            ->assertNoContent();

        $user = User::firstOrFail();

        Notification::assertSentTo($user, VerifyEmail::class);
        Notification::assertSentTo($user, RegisteredSMS::class);
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

    /**
     * @test
     */
    public function it_verifies_a_user_email()
    {
        /**
         * @var \App\Models\User
         */
        $user = User::factory()
            ->unverified()
            ->create();

        Event::fake();

        $route =  URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
                'token' => $user->createToken(Str::random(10))->plainTextToken,
            ]
        );

        $this->get($route)
            ->assertRedirect(frontend('/email-verified'));

        Event::assertDispatched(Verified::class);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    /**
     * @test
     */
    public function it_sends_a_change_email_verification_email()
    {

        $data = [
            'email' => $this->faker->safeEmail,
        ];

        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create(['role' => User::ADMIN]);

        $this->actingAs($user, 'sanctum');

        Notification::fake();

        $this->put(route('v1.users.update', ['user' => $user->id]), $data, ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonStructure(['data']);

        Notification::assertSentTo($user, ReVerifyEmail::class);
    }

    /**
     * @test
     */
    public function it_verifies_a_change_email_request()
    {
        /**
         * @var \App\Models\User
         */
        $user = User::factory()
            ->create(['email' => $this->faker->unique()->email]);

        $email = $this->faker->unique()->email;

        ChangeEmailRequest::withoutEvents(function () use ($user, $email) {
            $user->changeEmailRequests()->create(['email' => $email]);
        });

        /**
         * @var \App\Models\ChangeEmailRequest
         */
        $request = $user->changeEmailRequests()->firstOrFail();

        $route =  URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id' => $user->getKey(),
                'hash' => sha1($email),
                'token' => $user->createToken(Str::random(10))->plainTextToken,
                'request_id' => $request->id,
            ]
        );

        Event::fake();

        $this->get($route)
            ->assertRedirect(frontend('/email-verified'));

        Event::assertDispatched(Verified::class);

        $user = $user->fresh();

        $this->assertTrue($user->hasVerifiedEmail() && $user->email === $email);
    }
}
