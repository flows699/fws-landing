<?php

declare(strict_types=1);

use App\Filament\Exports\ContactMessageExporter;
use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Filament\Resources\ContactMessages\Pages\ListContactMessages;
use App\Filament\Resources\ContactMessages\Pages\ViewContactMessage;
use App\Models\ContactMessage;
use App\Models\User;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\Models\Export;
use Filament\Actions\Testing\TestAction;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->admin()->create());
});

it('lists the messages with the newest first', function () {
    $older = ContactMessage::factory()->create(['created_at' => now()->subDay()]);
    $newer = ContactMessage::factory()->create();

    Livewire::test(ListContactMessages::class)
        ->assertOk()
        ->assertCanSeeTableRecords([$newer, $older], inOrder: true);
});

it('marks the message as read when the view page is opened', function () {
    $message = ContactMessage::factory()->create();

    Livewire::test(ViewContactMessage::class, ['record' => $message->getRouteKey()])
        ->assertOk();

    expect($message->refresh()->read_at)->not->toBeNull();
});

it('keeps the original timestamp of an already read message', function () {
    $readAt = now()->subWeek();

    $message = ContactMessage::factory()->create(['read_at' => $readAt]);

    Livewire::test(ViewContactMessage::class, ['record' => $message->getRouteKey()]);

    expect($message->refresh()->read_at->toDateTimeString())->toBe($readAt->toDateTimeString());
});

it('badges the messages with their read state', function () {
    $unread = ContactMessage::factory()->create();
    $read = ContactMessage::factory()->read()->create();

    Livewire::test(ListContactMessages::class)
        ->assertTableColumnStateSet('status', 'Olvasatlan', $unread)
        ->assertTableColumnStateSet('status', 'Olvasott', $read);
});

it('counts only the unread messages in the navigation badge', function () {
    ContactMessage::factory()->count(2)->create();
    ContactMessage::factory()->read()->create();

    expect(ContactMessageResource::getNavigationBadge())->toBe('2');
});

it('has no badge when every message is read', function () {
    ContactMessage::factory()->read()->create();

    expect(ContactMessageResource::getNavigationBadge())->toBeNull();
});

it('exports the messages to a csv file', function () {
    // The export runs through a queued job batch; sync keeps it in-process.
    config()->set('queue.default', 'sync');
    Storage::fake('local');

    ContactMessage::factory()->create([
        'name' => 'Kovács Anna',
        'email' => 'anna@example.com',
        // A leading `=` would run as a formula in a spreadsheet.
        'message' => '=SUM(1,1)',
    ]);

    Livewire::test(ListContactMessages::class)
        ->callAction(TestAction::make(ExportAction::class)->table(), [
            'format' => ExportFormat::Csv->value,
        ])
        ->assertHasNoActionErrors();

    $export = Export::query()->sole();

    expect($export->exporter)->toBe(ContactMessageExporter::class)
        ->and($export->total_rows)->toBe(1);

    $csv = Storage::disk('local')->get("filament_exports/{$export->getKey()}/0000000000000001.csv");

    expect($csv)->toContain('Kovács Anna')
        ->and($csv)->toContain("'=SUM(1,1)");
});
