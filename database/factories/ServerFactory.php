<?php

namespace Database\Factories;

use App\Models\Server;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Server>
 */
class ServerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Server-'.fake()->unique()->lexify('???'),
            'cpu' => fake()->randomElement([16, 20, 24, 32, 48]),
            'memory' => fake()->randomElement([32, 48, 64, 96, 128]),
            'storage' => fake()->randomElement([250, 350, 500, 1000]),
            'latency' => fake()->numberBetween(5, 30),
            'max_services' => 3,
        ];
    }
}
