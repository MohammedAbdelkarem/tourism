<?php

namespace App\Notifications\Guide;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApprovmentNotification extends Notification
{
    use Queueable;
    protected $approvment , $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($approvment)
    {
        $this->approvment = $approvment;
        
        $this->message = ($approvment == 'accepted') 
        ? 'You have the permission to work now!' 
        : 'You do not have the permission to work now!';
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
            'approvment' => $this->approvment,
            'message' => $this->message,
        ];
    }
}
