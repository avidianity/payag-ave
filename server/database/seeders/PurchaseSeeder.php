<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Purchase::factory()
            ->count(20)
            ->for(
                Product::factory()
                    ->forCategory(),
            )
            ->forUser()
            ->create();
    }
}
