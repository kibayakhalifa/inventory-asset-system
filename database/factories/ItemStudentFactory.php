<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemStudent>
 */
class ItemStudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item_id' => Item::inRandomOrder()->value('id'),
            'student_id' => Student::inRandomOrder()->value('id'),
            'issued_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'returned_at' => $this->faker->optional()->dateTimeBetween('now', '+1 month'),
        ];
    }
}
