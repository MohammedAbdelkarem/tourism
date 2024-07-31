<?php

namespace App\Notifications\Guide;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WalletNotification extends Notification
{
    use Queueable;
    protected $amount , $trip_id , $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($amount , $trip_id)
    {
        $this->amount = $amount;
        $this->trip_id = $trip_id;
        $this->message = 'new amount has been added to your wallet';
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
            'amount' => $this->amount,
            'trip_id' => $this->trip_id,
            'message' => $this->message,
        ];
    }
}
