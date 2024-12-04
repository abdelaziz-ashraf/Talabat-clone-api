<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'latitude' => $this->faker->latitude(-20, 20),
            'longitude' => $this->faker->longitude(-50, 50),
            'addressable_id' => null,
            'addressable_type' => null,
            'active' => $this->faker->boolean,
        ];
    }
}
