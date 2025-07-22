<?php

namespace Database\Factories;

use App\Models\Lab;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $total = $this->faker->numberBetween(10, 100);
        return [
            'name'=>$this->faker->word,
            'type'=>$this->faker->randomElement(['uniform','stationery','book','equipment']),
            'quantity_total' => $total,
            'quantity_available'=> $this->faker->numberBetween(0,$total),
            'issued_once' => $this->faker->boolean,
            'reorder_threshold' => $this->faker->numberBetween(5, 20),
            'lab_id'=> Lab::factory(),
        ];
    }
}
