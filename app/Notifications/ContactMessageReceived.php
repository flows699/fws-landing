<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class ContactMessageReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public ContactMessage $contactMessage) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Build the mail sent to the administrators.
     *
     * The reply-to header points at the sender, so an answer can be written
     * straight from the mail client.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Új üzenet érkezett a weboldalról')
            ->greeting('Új kapcsolatfelvételi üzenet')
            ->line("Feladó: {$this->contactMessage->name} ({$this->contactMessage->email})")
            ->line('Üzenet:')
            ->line($this->contactMessage->message)
            ->action('Megnyitás az adminban', ContactMessageResource::getUrl('view', ['record' => $this->contactMessage]))
            ->salutation('Üdvözlettel, '.config('app.name'))
            ->replyTo($this->contactMessage->email, $this->contactMessage->name);
    }
}
