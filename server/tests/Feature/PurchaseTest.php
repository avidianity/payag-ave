<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function it_should_return_a_collection_of_purchases()
    {
        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create(['role' => User::ADMIN]);

        $this->actingAs($user, 'sanctum');

        $this->get(route('v1.purchases.index'), ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_should_return_a_purchase()
    {
        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create(['role' => User::ADMIN]);

        /**
         * @var \App\Models\Product
         */
        $product = Product::factory()->forCategory()->create();

        $this->actingAs($user, 'sanctum');

        /**
         * @var \App\Models\Purchase
         */
        $purchase = Purchase::factory()->create(['product_id' => $product->id, 'user_id' => $user->id]);

        $this->get(route('v1.purchases.show', ['purchase' => $purchase->id]), ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_should_create_a_purchase()
    {
        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create(['role' => User::ADMIN]);

        $data = [
            'from' => $this->faker->userName,
            'amount' => $this->faker->numberBetween(1, 20),
            'cost' => $this->faker->numberBetween(1, 20),
            'paid' => $this->faker->numberBetween(1, 20),
            'product_id' => Product::factory()->forCategory()->create()->id,
        ];

        $this->actingAs($user, 'sanctum');

        $this->post(route('v1.purchases.store'), $data, ['Accept' => 'application/json'])
            ->assertCreated()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_should_update_a_purchase()
    {
        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create(['role' => User::ADMIN]);

        /**
         * @var \App\Models\Product
         */
        $product = Product::factory()->forCategory()->create();

        $data = [
            'from' => $this->faker->userName,
            'amount' => $this->faker->numberBetween(1, 20),
            'cost' => $this->faker->numberBetween(1, 20),
            'paid' => $this->faker->numberBetween(1, 20),
            'product_id' => $product->id,
        ];

        $this->actingAs($user, 'sanctum');

        /**
         * @var \App\Models\Purchase
         */
        $purchase = Purchase::factory()->create(['product_id' => $product->id, 'user_id' => $user->id]);

        $this->put(route('v1.purchases.update', ['purchase' => $purchase->id]), $data, ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_should_delete_a_purchase()
    {
        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create(['role' => User::ADMIN]);

        /**
         * @var \App\Models\Product
         */
        $product = Product::factory()->forCategory()->create();

        $this->actingAs($user, 'sanctum');

        /**
         * @var \App\Models\Purchase
         */
        $purchase = Purchase::factory()->create(['product_id' => $product->id, 'user_id' => $user->id]);

        $this->delete(route('v1.purchases.destroy', ['purchase' => $purchase->id]), [], ['Accept' => 'application/json'])
            ->assertNoContent();
    }
}
