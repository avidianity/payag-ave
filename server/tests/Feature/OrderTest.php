<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function it_should_return_a_collection_of_orders()
    {
        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create(['role' => User::ADMIN]);

        $this->actingAs($user, 'sanctum');

        $this->get(route('v1.orders.index'), ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_should_return_an_order()
    {
        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create(['role' => User::ADMIN]);

        $this->actingAs($user, 'sanctum');

        User::factory()->create(['role' => User::CUSTOMER]);

        /**
         * @var \App\Models\Order
         */
        $order = Order::factory()->create();

        $this->get(route('v1.orders.show', ['order' => $order->id]), ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_should_create_a_order()
    {
        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create(['role' => User::ADMIN]);

        $this->actingAs($user, 'sanctum');

        /**
         * @var \App\Models\User
         */
        $customer = User::factory()->create(['role' => User::CUSTOMER]);

        /**
         * @var \App\Models\Category
         */
        $category = Category::factory()->create();

        /**
         * @var \App\Models\Product
         */
        $product = Product::factory()->create(['category_id' => $category->id]);

        $data = [
            'customer_id' => $customer->id,
            'biller_id' => $user->id,
            'paid' => $this->faker->numberBetween(1, 1000),
            'status' => Arr::random(Order::STATUSES),
            'products' => [
                ['id' => $product->id],
            ],
        ];

        $this->post(route('v1.orders.store'), $data, ['Accept' => 'application/json'])
            ->assertCreated()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_should_update_a_order()
    {
        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create(['role' => User::ADMIN]);

        $this->actingAs($user, 'sanctum');

        /**
         * @var \App\Models\User
         */
        $customer = User::factory()->create(['role' => User::CUSTOMER]);

        /**
         * @var \App\Models\Order
         */
        $order = Order::factory()->create();

        /**
         * @var \App\Models\Category
         */
        $category = Category::factory()->create();

        /**
         * @var \App\Models\Product
         */
        $product = Product::factory()->create(['category_id' => $category->id]);

        $data = [
            'customer_id' => $customer->id,
            'biller_id' => $user->id,
            'paid' => $this->faker->numberBetween(1, 1000),
            'status' => Arr::random(Order::STATUSES),
            'products' => [
                ['id' => $product->id],
            ],
        ];

        $this->put(route('v1.orders.update', ['order' => $order->id]), $data, ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_should_delete_a_order()
    {
        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create(['role' => User::ADMIN]);

        $this->actingAs($user, 'sanctum');

        /**
         * @var \App\Models\User
         */
        $customer = User::factory()->create(['role' => User::CUSTOMER]);

        /**
         * @var \App\Models\Order
         */
        $order = Order::factory()->create();

        $this->delete(route('v1.orders.destroy', ['order' => $order->id]), [], ['Accept' => 'application/json'])
            ->assertNoContent();
    }
}
