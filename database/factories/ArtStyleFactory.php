<?php

namespace Database\Factories;

use App\Models\ArtType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArtStyle>
 */
class ArtStyleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'art_type_id' => ArtType::factory(),
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'prompt' => $this->faker->sentence(),
        ];
    }
}
