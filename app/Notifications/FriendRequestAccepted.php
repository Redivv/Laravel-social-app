<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class FriendRequestAccepted extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $sender;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(object $sender)
    {
        $this->sender = $sender;
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

    public function toArray($notifiable)
    {
        return[
            'sender_name'       => $this->sender->name,
            'sender_picture'    => $this->sender->picture
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'sender_name'       => $this->sender->name,
            'sender_picture'    => $this->sender->picture
        ]);
    }

}
