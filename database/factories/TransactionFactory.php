<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use App\Models\Student;
use App\Models\Lab;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $labIds = Lab::pluck('id')->toArray();

        return [
            'item_id' => Item::factory(),
            'user_id' => User::factory(),
            'student_id' => Student::factory(),
            'lab_id' => fake()->boolean(70) ? fake()->randomElement($labIds) : null,
            'action' => $this->faker->randomElement(['issue', 'return']),
            'quantity' => $this->faker->numberBetween(1, 5),
            'condition' => $this->faker->randomElement(['new', 'good', 'worn', 'damaged']),
        ];
    }
}
