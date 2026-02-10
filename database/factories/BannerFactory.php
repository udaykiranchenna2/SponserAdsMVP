<?php

namespace Database\Factories;

use App\Enums\BannerStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Banner>
 */
class BannerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => fake()->uuid(),
            'title' => fake()->sentence(3),
            'image_path' => fake()->imageUrl(),
            'target_url' => fake()->url(),
            'link_text' => fake()->words(2, true),
            'status' => fake()->randomElement(BannerStatus::cases()),
        ];
    }
}
