<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Category;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vendor::factory(1000000)
            ->has(Category::factory(3)
                ->has(Product::factory(5), 'products'), 'categories')
            ->create()
            ->each(function ($vendor) {
                Address::factory(1)->create([
                    'addressable_id' => $vendor->id,
                    'addressable_type' => Vendor::class,
                ]);
            });

    }
}
