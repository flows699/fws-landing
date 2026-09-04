<?php

declare(strict_types=1);

use App\Filament\Pages\ManageHero;
use App\Models\HeroSection;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    Storage::fake('public');

    $this->actingAs(User::factory()->admin()->create());
});

it('creates the hero section on the first save', function () {
    Livewire::test(ManageHero::class)
        ->fillForm([
            'title' => 'Tér, fény, forma',
            'subtitle' => 'Belsőépítészeti stúdió Budapesten.',
            'cta_primary_label' => 'Kezdjük a tervezést',
            'cta_primary_url' => '#kapcsolat',
        ])
        ->set('data.image_path', [UploadedFile::fake()->image('hero.jpg')])
        ->call('save')
        ->assertHasNoFormErrors();

    $hero = HeroSection::query()->sole();

    expect($hero->title)->toBe('Tér, fény, forma')
        ->and($hero->cta_primary_url)->toBe('#kapcsolat');

    Storage::disk('public')->assertExists($hero->image_path);
});

it('loads the current hero section into the form', function () {
    $hero = HeroSection::factory()->create();

    Livewire::test(ManageHero::class)
        ->assertOk()
        ->assertFormSet([
            'title' => $hero->title,
            'subtitle' => $hero->subtitle,
            'cta_secondary_label' => $hero->cta_secondary_label,
        ]);
});

it('deletes the replaced background image when a new one is uploaded', function () {
    Storage::disk('public')->put('hero/old.jpg', 'fake-image');

    $hero = HeroSection::factory()->create(['image_path' => 'hero/old.jpg']);

    Livewire::test(ManageHero::class)
        // Livewire appends uploads to the existing state, so the old file has to go first.
        ->set('data.image_path', [])
        ->set('data.image_path', [UploadedFile::fake()->image('new.jpg')])
        ->call('save')
        ->assertHasNoFormErrors();

    Storage::disk('public')->assertMissing('hero/old.jpg');
    Storage::disk('public')->assertExists($hero->refresh()->image_path);
});
