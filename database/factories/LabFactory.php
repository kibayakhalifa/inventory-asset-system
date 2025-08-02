<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Lab;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lab>
 */
class LabFactory extends Factory
{
    protected $model = Lab::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $labs = ["Computer Lab","Physics Lab","Chemistry Lab","Biology Lab"];
        static $index = 0;
        return [
            'name' => $labs[$index++ % count($labs)],
            'location' => $this->faker->buildingNumber . ' ' . $this->faker->streetName,
            'description' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['active', 'closed', 'maintenance']),
        ];
    }
}
