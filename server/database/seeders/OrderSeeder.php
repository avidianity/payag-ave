<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::factory()
            ->count(50)
            ->has(
                OrderItem::factory()
                    ->count(20)
                    ->for(
                        Product::factory()
                            ->forCategory()
                    ),
                'items'
            )
            ->create();
    }
}
