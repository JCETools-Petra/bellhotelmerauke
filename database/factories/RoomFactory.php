<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'price' => $this->faker->numberBetween(500000, 2000000),
            'discount_percentage' => 0,
            'description' => $this->faker->paragraph(),
            'facilities' => $this->faker->sentence(),
            'image' => 'rooms/default.jpg',
            'is_available' => true,
            'meta_description' => $this->faker->sentence(),
            'seo_title' => $name,
        ];
    }
}
