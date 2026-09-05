<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\HeroSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class HeroSectionSeeder extends Seeder
{
    /**
     * Seed the single hero section row.
     */
    public function run(): void
    {
        $hero = HeroSection::query()->firstOrNew([]);

        $hero->fill([
            'title' => 'Tárgyak, amelyek kiállják az idő próbáját',
            'subtitle' => 'Letisztult ipari formatervezés a koncepciótól a sorozatgyártásig — felesleges díszítés nélkül.',
            'cta_primary_label' => 'Projekt megtekintése',
            'cta_primary_url' => '#munkaink',
            'cta_secondary_label' => 'A stúdióról',
            'cta_secondary_url' => '#studio',
            'image_path' => $this->copyImage(),
        ]);

        $hero->save();
    }

    /**
     * Copy the hero image from the seed assets to the public disk.
     */
    private function copyImage(): ?string
    {
        $sources = glob(database_path('seeders/assets/hero/hero.*')) ?: [];

        if ($sources === []) {
            $this->command?->warn('Nincs hero kép a database/seeders/assets/hero mappában, a hero kép nélkül jön létre.');

            return null;
        }

        $source = $sources[0];
        $target = 'hero/'.basename($source);

        Storage::disk('public')->put($target, (string) file_get_contents($source));

        return $target;
    }
}
