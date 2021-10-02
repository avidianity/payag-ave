<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function it_should_return_a_collection_of_users()
    {
        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create(['role' => User::ADMIN]);

        $this->actingAs($user, 'sanctum');

        $this->get(route('v1.users.index'), ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_should_return_a_user()
    {
        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create(['role' => User::ADMIN]);

        $this->actingAs($user, 'sanctum');

        $this->get(route('v1.users.show', ['user' => $user->id]), ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_should_create_a_user()
    {
        $data = [
            'name' => $this->faker->firstName,
            'email' => $this->faker->safeEmail,
            'phone' => '09' . $this->faker->numberBetween(111111111, 999999999),
            'password' => $this->faker->password,
            'status' => true,
            'role' => Arr::random(User::ROLES),
        ];

        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create(['role' => User::ADMIN]);

        $this->actingAs($user, 'sanctum');

        $this->post(route('v1.users.store'), $data, ['Accept' => 'application/json'])
            ->assertCreated()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_should_update_a_user()
    {
        $data = [
            'name' => $this->faker->firstName,
            'phone' => '09' . $this->faker->numberBetween(111111111, 999999999),
            'password' => $this->faker->password,
            'status' => true,
            'role' => Arr::random(User::ROLES),
        ];

        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create(['role' => User::ADMIN]);

        $this->actingAs($user, 'sanctum');

        $this->put(route('v1.users.update', ['user' => $user->id]), $data, ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_should_delete_a_user()
    {
        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create(['role' => User::ADMIN]);

        $this->actingAs($user, 'sanctum');

        $this->delete(route('v1.users.destroy', ['user' => $user->id]), [], ['Accept' => 'application/json'])
            ->assertNoContent();
    }

    /**
     * @test
     */
    public function it_should_create_a_user_with_picture()
    {
        Storage::fake();

        $data = [
            'name' => $this->faker->firstName,
            'email' => $this->faker->safeEmail,
            'phone' => '09' . $this->faker->numberBetween(111111111, 999999999),
            'password' => $this->faker->password,
            'status' => true,
            'role' => Arr::random(User::ROLES),
            'picture' => UploadedFile::fake()->image('image.png'),
        ];

        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create(['role' => User::ADMIN]);

        $this->actingAs($user, 'sanctum');

        $response = $this->post(route('v1.users.store'), $data, ['Accept' => 'application/json']);

        $response->assertCreated()
            ->assertJsonStructure(['data']);

        Storage::assertExists(User::findOrFail($response->json('data')['id'])->picture->url);
    }
}
