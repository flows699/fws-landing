<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\ContactMessage;

final class StoreContactMessage
{
    /**
     * Store an incoming contact message.
     *
     * @param  array{name: string, email: string, message: string}  $attributes
     */
    public function __invoke(array $attributes, ?string $ipAddress = null): ContactMessage
    {
        return ContactMessage::create([
            ...$attributes,
            'ip_address' => $ipAddress,
        ]);
    }
}
