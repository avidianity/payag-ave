<?php

namespace Database\Seeders;

use App\Models\Order;
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
            ->hasAttached(
                Product::factory()
                    ->forCategory()
                    ->count(5)
            )
            ->create();
    }
}
