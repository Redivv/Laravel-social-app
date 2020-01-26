<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class PendingPartnerRequest extends Notification implements ShouldQueue
{
    use Queueable;

    public $sender;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(object $user)
    {
        $this->sender = $user;
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
            'sender_id'         => $this->sender->id,
            'sender_name'       => $this->sender->name,
            'sender_picture'    => $this->sender->picture
        ];
    }


    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'sender_id'         => $this->sender->id,
            'sender_name'       => $this->sender->name,
            'sender_picture'    => $this->sender->picture
        ]);
    }
}
