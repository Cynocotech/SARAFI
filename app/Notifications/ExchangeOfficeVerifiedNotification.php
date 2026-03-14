<?php

namespace App\Notifications;

use App\Models\ExchangeOffice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExchangeOfficeVerifiedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ExchangeOffice $exchangeOffice
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $name = $this->exchangeOffice->name;
        return (new MailMessage)
            ->subject('Your exchange office has been verified')
            ->line("Your exchange office \"{$name}\" has been verified successfully.")
            ->line('You can now manage your listing and rates in the dashboard.')
            ->action('Go to Dashboard', url('/dashboard'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'exchange_office_verified',
            'exchange_office_id' => $this->exchangeOffice->id,
            'message' => "Your exchange office \"{$this->exchangeOffice->name}\" has been verified.",
        ];
    }
}
