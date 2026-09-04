<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\HeroSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HeroSection>
 */
class HeroSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(5),
            'subtitle' => fake()->paragraph(2),
            'cta_primary_label' => 'Kezdjük a tervezést',
            'cta_primary_url' => '#kapcsolat',
            'cta_secondary_label' => 'A stúdióról',
            'cta_secondary_url' => '#studio',
            'image_path' => 'hero/hero.jpg',
        ];
    }

    /**
     * Indicate that the hero section has no background image.
     */
    public function withoutImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'image_path' => null,
        ]);
    }
}
