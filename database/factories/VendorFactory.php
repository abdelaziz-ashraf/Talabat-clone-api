<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vendor>
 */
class VendorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'image' => $this->faker->imageUrl(640, 480, 'business', true),
            'code' => $this->faker->unique()->regexify('[A-Za-z0-9]{6}'),
            'password' => Hash::make('password'),
        ];
    }
}
