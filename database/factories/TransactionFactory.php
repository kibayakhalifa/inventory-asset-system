<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use App\Models\Student;
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
        return [
            'item_id' => Item::factory(),
            'user_id' => User::factory(),
            'student_id' => Student::factory(),
            'action' => $this->faker->randomElement(['issued', 'returned']),
            'quantity' => $this->faker->numberBetween(1, 5),
        ];
    }
}
