<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'title',
    'slug',
    'cover_path',
    'published_at',
    'is_published',
    'sort_order',
])]
class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

    /**
     * Keep the public disk free of cover images no record points to any more.
     */
    protected static function booted(): void
    {
        static::updating(function (self $project): void {
            $original = $project->getOriginal('cover_path');

            if ($project->isDirty('cover_path') && filled($original)) {
                Storage::disk('public')->delete($original);
            }
        });

        static::deleting(function (self $project): void {
            Storage::disk('public')->delete($project->cover_path);
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'date',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Scope a query to the projects shown on the landing page.
     *
     * The publish date breaks ties, so equal sort orders still list in a
     * stable order.
     */
    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('is_published', true)
            ->orderBy('sort_order')
            ->orderByDesc('published_at');
    }
}
