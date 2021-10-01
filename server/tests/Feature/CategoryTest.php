<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function it_should_return_a_collection_of_categories()
    {
        $this->get(route('v1.categories.index'), ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_should_return_a_category()
    {
        /**
         * @var \App\Models\Category
         */
        $category = Category::factory()->create();

        $this->get(route('v1.categories.show', ['category' => $category->id]), ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_should_create_a_category()
    {
        $data = [
            'code' => $this->faker->text(5),
            'name' => $this->faker->streetName,
        ];

        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create(['role' => User::ADMIN]);

        $this->actingAs($user, 'sanctum');

        $this->post(route('v1.categories.store'), $data, ['Accept' => 'application/json'])
            ->assertCreated()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_should_update_a_category()
    {
        $data = [
            'code' => $this->faker->text(5),
            'name' => $this->faker->streetName,
        ];

        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create(['role' => User::ADMIN]);

        $this->actingAs($user, 'sanctum');

        /**
         * @var \App\Models\Category
         */
        $category = Category::factory()->create();

        $this->put(route('v1.categories.update', ['category' => $category->id]), $data, ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_should_delete_a_category()
    {
        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create(['role' => User::ADMIN]);

        $this->actingAs($user, 'sanctum');

        /**
         * @var \App\Models\Category
         */
        $category = Category::factory()->create();

        $this->delete(route('v1.categories.update', ['category' => $category->id]), [], ['Accept' => 'application/json'])
            ->assertNoContent();
    }
}
