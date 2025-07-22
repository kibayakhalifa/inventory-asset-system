<?php

namespace Database\Factories;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=> $this->faker->name,
            'registration_number'=>strtoupper($this->faker->unique()->bothify('TUM-2025-####')),
            'class'=> $this->faker->randomElement(['Form 1','Form 2', 'Form 3', 'Form 4']),
            'gender'=>$this->faker->randomElement(['Male','Female']),
        ];
    }
}
