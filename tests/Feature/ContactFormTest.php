<?php

declare(strict_types=1);

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Models\ContactMessage;
use App\Models\User;
use App\Notifications\ContactMessageReceived;
use Illuminate\Support\Facades\Notification;

function contactPayload(array $overrides = []): array
{
    return [
        'name' => 'Kovács Anna',
        'email' => 'anna@example.com',
        'message' => 'Egy új termékcsalád formatervéhez keresünk partnert.',
        ...$overrides,
    ];
}

it('stores a contact message', function () {
    $this->postJson(route('contact.store'), contactPayload())
        ->assertCreated()
        ->assertJsonPath('message', 'Köszönjük az üzenetet, hamarosan válaszolunk.');

    $message = ContactMessage::sole();

    expect($message->name)->toBe('Kovács Anna')
        ->and($message->email)->toBe('anna@example.com')
        ->and($message->ip_address)->not->toBeNull()
        ->and($message->read_at)->toBeNull();
});

it('rejects an invalid submission with field errors', function () {
    $this->postJson(route('contact.store'), contactPayload([
        'name' => '',
        'email' => 'nem-email',
        'message' => 'rövid',
    ]))
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'email', 'message']);

    expect(ContactMessage::count())->toBe(0);
});

it('rejects a submission that fills the honeypot', function () {
    $this->postJson(route('contact.store'), contactPayload(['website' => 'https://spam.example']))
        ->assertStatus(422)
        ->assertJsonValidationErrors(['website']);

    expect(ContactMessage::count())->toBe(0);
});

it('rate limits after five submissions in a minute', function () {
    foreach (range(1, 5) as $ignored) {
        $this->postJson(route('contact.store'), contactPayload())->assertCreated();
    }

    $this->postJson(route('contact.store'), contactPayload())->assertTooManyRequests();

    expect(ContactMessage::count())->toBe(5);
});

it('notifies the administrators about a new message', function () {
    Notification::fake();

    $admin = User::factory()->admin()->create();
    $visitor = User::factory()->create();

    $this->postJson(route('contact.store'), contactPayload())->assertCreated();

    Notification::assertSentTo(
        [$admin],
        ContactMessageReceived::class,
        fn (ContactMessageReceived $notification) => $notification->contactMessage->is(ContactMessage::sole())
    );

    Notification::assertNotSentTo([$visitor], ContactMessageReceived::class);
});

it('links the notification mail to the message in the admin', function () {
    $admin = User::factory()->admin()->create();
    $message = ContactMessage::factory()->create();

    $mail = (new ContactMessageReceived($message))->toMail($admin);

    expect($mail->actionUrl)->toBe(ContactMessageResource::getUrl('view', ['record' => $message]))
        ->and($mail->replyTo)->toBe([[$message->email, $message->name]]);
});
