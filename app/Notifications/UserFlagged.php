<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class UserFlagged extends Notification
{
    use Queueable;

    public $user_name;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user_name)
    {
        $this->user_name = $user_name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','broadcast'];
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
            'user_name'     => $this->user_name
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'user_name'     => $this->user_name
        ]);
    }
}
