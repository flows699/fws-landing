<?php

declare(strict_types=1);

use App\Models\ContactMessage;

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
