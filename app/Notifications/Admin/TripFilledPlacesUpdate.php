<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TripFilledPlacesUpdate extends Notification
{
    use Queueable;
    protected $trip;
    protected $filled_places;

    /**
     * Create a new notification instance.
     */
    public function __construct( $trip,$filled_places)
    {
        $this->trip = $trip;
        $this->filled_places = $filled_places;
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
            "trip" => $this->trip,
            "filled_places" => $this->filled_places,
        ];
    }
}
