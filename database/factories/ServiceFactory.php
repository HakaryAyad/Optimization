<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'server_id' => null,
            'name' => fake()->randomElement(['Auth', 'Billing', 'Analytics', 'Cache', 'Gateway', 'Search', 'Notifications', 'Transcoder', 'Logging', 'Inventory', 'Profiles', 'Reports']).' Service',
            'cpu' => fake()->numberBetween(2, 14),
            'memory' => fake()->numberBetween(4, 24),
            'storage' => fake()->numberBetween(20, 150),
        ];
    }
}
