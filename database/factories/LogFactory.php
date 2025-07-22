<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Log;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Log>
 */
class LogFactory extends Factory
{
    protected $model = Log::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'action' => $this->faker->randomElement(['issued item', 'returned item', 'updated stock']),
            'description' => $this->faker->sentence(),
            'user_id' => User::inRandomOrder()->value('id'),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
