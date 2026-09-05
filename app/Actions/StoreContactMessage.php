<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\ContactMessage;
use App\Models\User;
use App\Notifications\ContactMessageReceived;
use Illuminate\Support\Facades\Notification;

final class StoreContactMessage
{
    /**
     * Store an incoming contact message and notify the administrators.
     *
     * @param  array{name: string, email: string, message: string}  $attributes
     */
    public function __invoke(array $attributes, ?string $ipAddress = null): ContactMessage
    {
        $contactMessage = ContactMessage::create([
            ...$attributes,
            'ip_address' => $ipAddress,
        ]);

        Notification::send(
            User::query()->where('is_admin', true)->get(),
            new ContactMessageReceived($contactMessage),
        );

        return $contactMessage;
    }
}
