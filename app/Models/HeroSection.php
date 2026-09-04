<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\HeroSectionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'subtitle',
    'cta_primary_label',
    'cta_primary_url',
    'cta_secondary_label',
    'cta_secondary_url',
    'image_path',
])]
class HeroSection extends Model
{
    /** @use HasFactory<HeroSectionFactory> */
    use HasFactory;

    /**
     * The single hero section the landing page renders.
     *
     * Returns an unsaved instance when the table is still empty, so the
     * frontend and the admin page can work without a null check.
     */
    public static function current(): self
    {
        return static::query()->first() ?? new self;
    }
}
