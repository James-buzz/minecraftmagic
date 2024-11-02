<?php

namespace Database\Factories;

use App\Models\Generation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Generation>
 */
class GenerationFactory extends Factory
{
    protected $model = Generation::class;

    public function definition(): array
    {
        return [
            'id' => Str::ulid(),
            'user_id' => User::factory(),
            'status' => $this->faker->randomElement(['pending', 'processing', 'completed', 'failed']),
            'art_type' => $this->faker->word(),
            'art_style' => $this->faker->word(),
            'metadata' => [],
            'file_path' => $this->faker->optional()->filePath(),
            'thumbnail_file_path' => $this->faker->optional()->filePath(),
            'failed_reason' => null,
        ];
    }

    public function pending(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    public function processing(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'processing',
        ]);
    }

    public function completed(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'file_path' => $this->faker->filePath(),
            'thumbnail_file_path' => $this->faker->filePath(),
        ]);
    }

    public function failed(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
        ]);
    }
}
