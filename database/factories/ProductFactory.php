<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $name = fake()->words(3, true);
        return [
            'category_id' => \App\Models\Category::factory(),
            'brand_id' => \App\Models\Brand::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 100, 1000),
            'original_price' => fake()->randomFloat(2, 1000, 2000),
            'image' => fake()->imageUrl(),
            'gender' => fake()->randomElement(['male', 'female', 'unisex']),
            'type' => 'original',
            'featured' => fake()->boolean(),
            'trending' => fake()->boolean(),
        ];
    }
}
