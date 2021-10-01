<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => $this->faker->text(5),
            'name' => $this->faker->streetName,
            'price' => $this->faker->numberBetween(0, 1000),
            'cost' => $this->faker->numberBetween(0, 1000),
            'quantity' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
