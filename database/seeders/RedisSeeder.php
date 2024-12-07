<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RedisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $addresses = \App\Models\Address::where('addressable_type', \App\Models\Vendor::class)->get();
        foreach($addresses as $address) {
            \Illuminate\Support\Facades\Redis::command('geoadd', [
                'vendors-locations',
                'longitude' => $address->longitude,
                'latitude' => $address->latitude,
                'vendor_id' => $address->addressable_id,
                'image' => $address->addressable->image,
                'name' => $addresses->addressable->name,
            ]);
        }
    }
}
