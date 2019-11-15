<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewFriendPost extends Notification
{
    use Queueable;

    public $author;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(object $author)
    {
        $this->author = $author;
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
            'author_name'   =>$this->author->name,
            'author_image'  =>$this->author->picture
        ];
    }
}
