<?php

namespace App\Notifications\Guide;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UniqueIdNotification extends Notification
{
    use Queueable;
    protected $status , $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($status)
    {
        $this->status = $status;

        $this->message = ($status == 'able') 
        ? 'You can modify the unique id now!' 
        : 'You can not modify the unique id!';
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
            'status' => $this->status,
            'message' => $this->message,
        ];
    }
}
