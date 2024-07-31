<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TripAcceptedByGuide extends Notification
{
    use Queueable;
    protected $trip;
    protected $message;
    protected $trip_name;
    /**
     * Create a new notification instance.
     */
    public function __construct( $trip_name ,$trip)
    { $this->trip_name = $trip_name;
        $this->trip = $trip;
        $this->message = "$trip_name trip accepted by guide";
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
            'message' => $this->message,
        ];
    }
}
