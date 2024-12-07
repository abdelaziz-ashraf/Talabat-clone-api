<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::factory(2200)
            ->create()
            ->each(function ($customer) {
                Address::factory(2)->create([
                    'addressable_id' => $customer->id,
                    'addressable_type' => Customer::class,
                ]);
            });
    }
}
