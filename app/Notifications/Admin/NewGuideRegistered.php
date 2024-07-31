<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewGuideRegistered extends Notification
{
    use Queueable;
    protected $guide;
    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($guide)
    {
        
        $this->guide = $guide;
        $this->message = 'A new guide has been registered ';
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
            "guide" => $this->guide,
            'message' => $this->message,
        ];
    }
}
