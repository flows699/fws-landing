<?php

declare(strict_types=1);

use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Filament\Resources\Projects\Pages\ListProjects;
use App\Models\Project;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\Testing\TestAction;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    Storage::fake('public');

    $this->actingAs(User::factory()->admin()->create());
});

it('stores the record and the cover image when a project is created', function () {
    Livewire::test(CreateProject::class)
        ->fillForm([
            'title' => 'Axis munkalámpa',
            'slug' => 'axis-munkalampa',
            'published_at' => '2026-06-18',
            'is_published' => true,
        ])
        ->set('data.cover_path', [UploadedFile::fake()->image('axis.jpg')])
        ->call('create')
        ->assertHasNoFormErrors();

    $project = Project::query()->sole();

    expect($project->title)->toBe('Axis munkalámpa')
        ->and($project->slug)->toBe('axis-munkalampa');

    Storage::disk('public')->assertExists($project->cover_path);
});

it('deletes the replaced cover image when a new one is uploaded', function () {
    Storage::disk('public')->put('projects/old.jpg', 'fake-image');

    $project = Project::factory()->create(['cover_path' => 'projects/old.jpg']);

    Livewire::test(EditProject::class, ['record' => $project->getRouteKey()])
        // Livewire appends uploads to the existing state, so the old file has to go first.
        ->set('data.cover_path', [])
        ->set('data.cover_path', [UploadedFile::fake()->image('new.jpg')])
        ->call('save')
        ->assertHasNoFormErrors();

    Storage::disk('public')->assertMissing('projects/old.jpg');
    Storage::disk('public')->assertExists($project->refresh()->cover_path);
});

it('deletes the cover image together with the project', function () {
    Storage::disk('public')->put('projects/cover.jpg', 'fake-image');

    $project = Project::factory()->create(['cover_path' => 'projects/cover.jpg']);

    Livewire::test(ListProjects::class)
        ->callAction(TestAction::make(DeleteAction::class)->table($project));

    $this->assertModelMissing($project);

    Storage::disk('public')->assertMissing('projects/cover.jpg');
});

it('lists the projects on the index page', function () {
    $projects = Project::factory()->count(3)->create();

    Livewire::test(ListProjects::class)
        ->assertOk()
        ->assertCanSeeTableRecords($projects);
});
