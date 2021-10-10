<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\File;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::factory()
            ->count(100)
            ->has(
                File::factory(),
                'picture'
            )
            ->forCategory()
            ->create();
    }
}
