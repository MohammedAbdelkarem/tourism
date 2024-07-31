<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentOnTrip extends Notification
{
    use Queueable;
    protected $trip;
    protected $trip_name;
    protected $comment;
    protected $user;
    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($trip_name,$trip,$comment,$user)
    {   $this->trip_name = $trip_name;
        $this->trip = $trip;
        $this->comment = $comment;
        $this-> user= $user;
        $this->message = "a new comment added to $trip_name trip";
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
            "comment" => $this->comment,
            "user"=> $this ->user,
            'message' => $this->message,
        ];
    }
}
