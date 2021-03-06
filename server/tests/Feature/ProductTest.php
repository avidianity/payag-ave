<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\DOMService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function it_should_return_a_collection_of_products()
    {
        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $this->get(route('v1.products.index'), ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_should_return_a_product()
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

        $this->get(route('v1.products.show', ['product' => $product->id]), ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_should_create_a_product()
    {
        /**
         * @var \App\Models\Category
         */
        $category = Category::factory()->create();

        $data = [
            'code' => $this->faker->text(5),
            'name' => $this->faker->streetName,
            'price' => $this->faker->numberBetween(0, 1000),
            'cost' => $this->faker->numberBetween(0, 1000),
            'quantity' => $this->faker->numberBetween(0, 1000),
            'category_id' => $category->id,
        ];

        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create(['role' => User::ADMIN]);

        $this->actingAs($user, 'sanctum');

        $this->post(route('v1.products.store'), $data, ['Accept' => 'application/json'])
            ->assertCreated()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_should_update_a_product()
    {
        $data = [
            'code' => $this->faker->text(5),
            'name' => $this->faker->streetName,
            'price' => $this->faker->numberBetween(0, 1000),
            'cost' => $this->faker->numberBetween(0, 1000),
            'quantity' => $this->faker->numberBetween(0, 1000),
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

        /**
         * @var \App\Models\Product
         */
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->put(route('v1.products.update', ['product' => $product->id]), $data, ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_should_delete_a_product()
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

        /**
         * @var \App\Models\Product
         */
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->delete(route('v1.products.destroy', ['product' => $product->id]), [], ['Accept' => 'application/json'])
            ->assertNoContent();
    }

    /**
     * @test
     */
    public function it_returns_products_from_a_category()
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

        $this->get(route('v1.categories.products.index', ['category' => $category->id]), ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_returns_a_product_from_a_category()
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

        $this->get(
            route(
                'v1.categories.products.show',
                [
                    'category' => $category->id,
                    'product' => $product->id,
                ]
            ),
            ['Accept' => 'application/json']
        )
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    /**
     * @test
     */
    public function it_should_create_a_product_with_picture()
    {
        /**
         * @var \App\Models\Category
         */
        $category = Category::factory()->create();

        Storage::fake();

        $data = [
            'code' => $this->faker->text(5),
            'name' => $this->faker->streetName,
            'price' => $this->faker->numberBetween(0, 1000),
            'cost' => $this->faker->numberBetween(0, 1000),
            'quantity' => $this->faker->numberBetween(0, 1000),
            'category_id' => $category->id,
            'picture' => UploadedFile::fake()->image('image.png'),
        ];

        /**
         * @var \App\Models\User
         */
        $user = User::factory()->create(['role' => User::ADMIN]);

        $this->actingAs($user, 'sanctum');

        $this->post(route('v1.products.store'), $data, ['Accept' => 'application/json'])
            ->assertCreated()
            ->assertJsonStructure(['data']);

        Storage::assertExists(Product::firstOrFail()->picture->url);
    }

    /**
     * @test
     */
    public function it_should_update_a_product_with_picture()
    {
        Storage::fake();

        $data = [
            'code' => $this->faker->text(5),
            'name' => $this->faker->streetName,
            'price' => $this->faker->numberBetween(0, 1000),
            'cost' => $this->faker->numberBetween(0, 1000),
            'quantity' => $this->faker->numberBetween(0, 1000),
            'picture' => UploadedFile::fake()->image('image.png'),
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

        /**
         * @var \App\Models\Product
         */
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->put(route('v1.products.update', ['product' => $product->id]), $data, ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonStructure(['data']);

        Storage::assertExists($product->picture->fresh()->url);
    }

    /**
     * @test
     */
    public function it_should_create_a_product_with_a_sanitized_description()
    {
        $html = <<<HTML
<html>
    <head>
        <title>My Title</title>
    </head>
    <body>
        <div>Hello World</div>
        <script>console.log('Hello World! :)')</script>
        <script>alert('Mwehehehe')</script>
    </body>
</html>
HTML;

        /**
         * @var \App\Models\Category
         */
        $category = Category::factory()->create();

        /**
         * @var \App\Models\Product
         */
        $product = Product::factory()->create(['category_id' => $category->id, 'description' => $html]);

        $service = app(DOMService::class);

        $this->assertFalse($service->hasTag($product->description, 'script'));
    }
}
