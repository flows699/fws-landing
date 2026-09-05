<?php

declare(strict_types=1);

use App\Models\HeroSection;
use App\Models\Project;

it('renders the hero section from the database', function () {
    $hero = HeroSection::factory()->create([
        'title' => 'Ipari formaterv, ami kibírja a mindennapokat',
        'subtitle' => 'Az ötlettől a sorozatgyártásig egy csapat visz végig.',
    ]);

    $this->get(route('landing'))
        ->assertOk()
        ->assertSee($hero->title, escape: false)
        ->assertSee($hero->subtitle, escape: false)
        ->assertSee($hero->cta_primary_label, escape: false)
        ->assertSee($hero->cta_secondary_label, escape: false);
});

it('lists the published projects', function () {
    HeroSection::factory()->create();

    $project = Project::factory()->create([
        'title' => 'Axis munkalámpa',
        'published_at' => '2026-06-18',
    ]);

    $this->get(route('landing'))
        ->assertOk()
        ->assertSee($project->title, escape: false)
        ->assertSee('2026.06.18');
});

it('hides unpublished projects', function () {
    HeroSection::factory()->create();

    $hidden = Project::factory()->unpublished()->create(['title' => 'Rejtett referencia']);

    $this->get(route('landing'))
        ->assertOk()
        ->assertDontSee($hidden->title, escape: false);
});

it('renders without a hero row', function () {
    $this->get(route('landing'))->assertOk();
});
