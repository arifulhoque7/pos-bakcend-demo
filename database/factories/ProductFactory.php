<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'SKU' => $this->faker->unique()->lexify('SKU-????'),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'initial_stock_quantity' => 10,
            'current_stock_quantity' => 10,
            'category_id' => Category::inRandomOrder()->value('id') ?? null,
        ];
    }
}
