<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminWallet extends Notification
{
    use Queueable;
protected $trip;
protected $amount;
    /**
     * Create a new notification instance.
     */
    public function __construct($trip, $amount)
    {
        $this->trip = $trip; 
        $this->amount = $amount;
    }

    /**
     * Get the notification's delivery channels.
     *1
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
            "trip"=>$this->trip,
            "amount" => $this->amount,
        ];
    }
}
