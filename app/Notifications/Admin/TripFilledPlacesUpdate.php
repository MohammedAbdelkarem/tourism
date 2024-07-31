<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TripFilledPlacesUpdate extends Notification
{
    use Queueable;
    protected $trip_name;
    protected $trip;
    protected $filled_places;
    protected $message;
    /**
     * Create a new notification instance.
     */
    public function __construct( $trip_name,$trip,$filled_places)
    {   $this->trip_name = $trip_name;
        $this->trip = $trip;
        $this->filled_places = $filled_places;
        $this->message = "The $trip_name trip has been filled with $filled_places seats ";
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
            'message' => $this->message,
        ];
    }
}
