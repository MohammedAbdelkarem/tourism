<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NoteNotification extends Notification
{
    use Queueable;

    protected $note , $message , $facility_in_day_id;

    /**
     * Create a new notification instance.
     */
    public function __construct($note , $facility_in_day_id)
    {
        $this->message = 'new note for the current reservation';
        $this->note = $note;
        $this->facility_in_day_id = $facility_in_day_id;
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
            'note' => $this->note,
            'message' => $this->message,
            'facility_in_day_id' => $this->facility_in_day_id,
        ];
    }
}
