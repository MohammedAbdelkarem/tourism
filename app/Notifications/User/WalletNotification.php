<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WalletNotification extends Notification
{
    use Queueable;

    protected $reservation_id;
    protected $amount;
    protected $type;
    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($reservation_id , $amount , $type)
    {
        $this->reservation_id = $reservation_id;
        $this->amount = $amount;
        $this->type = $type;
        if($this->type == 'add')
        {
            $this->message = 'new amount added to your wallet';
        }
        else
        {
            $this->message = 'new amount taken from your wallet by the reservation';
        }
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
            'reservatioin_id' => $this->reservation_id,
            'amount' => $this->amount,
            'type' => $this->type,
            'message' => $this->message,
        ];
    }
}
