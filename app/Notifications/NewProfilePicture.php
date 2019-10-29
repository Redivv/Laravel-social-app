<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewProfilePicture extends Notification
{
    use Queueable;

    public $user_name;
    public $image;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user_name,$image)
    {
        $this->user_name = $user_name;
        $this->image = $image;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'user_name'     => $this->user_name,
            'image'         => $this->image,
        ];
    }
}
