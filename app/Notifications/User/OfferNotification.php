<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OfferNotification extends Notification
{
    use Queueable;

    protected $trip_id;
    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($trip_id)
    {
        $this->trip_id = $trip_id;
        $this->message = 'new offer!!';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'trip_id' => $this->trip_id,
            'message' => $this->message,
        ];
    }
}
