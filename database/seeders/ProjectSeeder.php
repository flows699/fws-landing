<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    /**
     * Sample titles and publish dates for the reference list.
     *
     * @var list<array{title: string, published_at: string}>
     */
    private const PROJECTS = [
        ['title' => 'Acél kényelmi eszközök', 'published_at' => '2026-05-28'],
        ['title' => 'Beltéri szerkezetek és térelválasztók', 'published_at' => '2026-05-14'],
        ['title' => 'Kültéri építészeti megoldások', 'published_at' => '2026-04-30'],
        ['title' => 'Egyedi fémszerkezetek', 'published_at' => '2026-04-12'],
    ];

    /**
     * Seed the reference projects shown on the landing page.
     */
    public function run(): void
    {
        $covers = $this->copyCovers();

        if ($covers === []) {
            $this->command?->warn('Nincs borítókép a database/seeders/assets/projects mappában, a referenciák placeholder útvonallal jönnek létre.');
        }

        $count = max(count($covers), 4);

        foreach (array_slice(self::PROJECTS, 0, $count) as $index => $project) {
            Project::query()->updateOrCreate(
                ['slug' => Str::slug($project['title'])],
                [
                    'title' => $project['title'],
                    'cover_path' => $covers[$index] ?? 'projects/placeholder.jpg',
                    'published_at' => $project['published_at'],
                    'is_published' => true,
                    'sort_order' => $index,
                ],
            );
        }
    }

    /**
     * Copy the cover images from the seed assets to the public disk.
     *
     * @return list<string>
     */
    private function copyCovers(): array
    {
        $sources = glob(database_path('seeders/assets/projects/*.{jpg,jpeg,png,webp}'), GLOB_BRACE) ?: [];
        sort($sources);

        $paths = [];

        foreach ($sources as $source) {
            $target = 'projects/'.basename($source);

            Storage::disk('public')->put($target, (string) file_get_contents($source));

            $paths[] = $target;
        }

        return $paths;
    }
}
