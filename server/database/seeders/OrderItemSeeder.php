<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderItem::factory()
            ->count(50)
            ->for(
                Product::factory()
                    ->forCategory()
            )
            ->forOrder()
            ->create();
    }
}
