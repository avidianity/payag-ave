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

class SelfOrderTest extends TestCase
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
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $this->get(route('v1.self.orders.index'), ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_should_return_an_order()
    {
        User::factory()->create(['role' => User::EMPLOYEE]);

        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        /**
         * @var \App\Models\Order
         */
        $order = Order::factory()->create(['customer_id' => $user->id]);

        $this->get(route('v1.self.orders.show', ['order' => $order->id]), ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_should_create_an_order()
    {
        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        /**
         * @var \App\Models\Category
         */
        $category = Category::factory()->create();

        /**
         * @var \App\Models\Product
         */
        $product = Product::factory()->create(['category_id' => $category->id]);

        $data = [
            'products' => [
                ['id' => $product->id, 'quantity' => $this->faker->numberBetween(1, 20)],
            ],
        ];

        $this->post(route('v1.self.orders.store'), $data, ['Accept' => 'application/json'])
            ->assertCreated()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_should_update_an_order()
    {
        User::factory()->create(['role' => User::EMPLOYEE]);

        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create(['role' => User::ADMIN]);

        $this->actingAs($user, 'sanctum');

        /**
         * @var \App\Models\Order
         */
        $order = Order::factory()->create([
            'customer_id' => $user->id,
            'status' => Order::UNPAID,
        ]);

        /**
         * @var \App\Models\Category
         */
        $category = Category::factory()->create();

        /**
         * @var \App\Models\Product
         */
        $product = Product::factory()->create(['category_id' => $category->id]);

        $data = [
            'products' => [
                ['id' => $product->id, 'quantity' => $this->faker->numberBetween(1, 20)],
            ],
        ];

        $this->put(route('v1.self.orders.update', ['order' => $order->id]), $data, ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_should_delete_an_order()
    {
        User::factory()->create(['role' => User::EMPLOYEE]);

        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        /**
         * @var \App\Models\Order
         */
        $order = Order::factory()->create([
            'customer_id' => $user->id,
            'status' => Order::UNPAID,
        ]);

        $this->delete(route('v1.self.orders.destroy', ['order' => $order->id]), [], ['Accept' => 'application/json'])
            ->assertNoContent();
    }
}
