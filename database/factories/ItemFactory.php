<?php

namespace Database\Factories;

use App\Models\Lab;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
{
    $type = $this->faker->randomElement(['uniform', 'stationery', 'equipment', 'chemical']);
    $total = $this->faker->numberBetween(10, 100);

    return [
        'name' => $this->faker->word,
        'type' => $type,
        'quantity_total' => $total,
        'quantity_available' => $this->faker->numberBetween(0, $total),
        'issued_once' => $this->faker->boolean,
        'reorder_threshold' => $this->faker->numberBetween(5, 20),
        'lab_id' => in_array($type, ['equipment', 'chemical']) ? Lab::inRandomOrder()->first()?->id : null,
    ];
}

}
