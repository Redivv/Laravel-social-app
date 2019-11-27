<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class UserNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $user;
    public $link;
    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(object $user, string $link, string $message)
    {
        $this->user          = $user;
        $this->link          = $link;
        $this->message       = $message;
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
            'user_name'         =>$this->user->name,
            'user_image'        =>$this->user->picture,
            'link'              =>$this->link,
            'message'           =>$this->message
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'user_name'         =>$this->user->name,
            'user_image'        =>$this->user->picture,
            'link'              =>$this->link,
            'message'           =>$this->message
        ]);
    }

}
