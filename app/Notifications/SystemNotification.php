<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class SystemNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $message;
    public $color;
    public $link;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $message,string $color,string $link)
    {
        $this->message = $message;
        $this->color = $color;
        $this->link = $link;
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
            'message'       => $this->message,
            'color'         => $this->color,
            'link'          => $this->link
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message'       => $this->message,
            'color'         => $this->color,
            'link'          => $this->link
        ]);
    }
}