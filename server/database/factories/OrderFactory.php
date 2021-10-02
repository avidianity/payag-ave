<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer_id' => Arr::random(User::all()->map->id->toArray()),
            'biller_id' => Arr::random(User::roles([User::ADMIN, User::EMPLOYEE])->get()->map->id->toArray()),
            'paid' => $this->faker->numberBetween(1, 1000),
            'status' => Arr::random(Order::STATUSES),
        ];
    }
}
