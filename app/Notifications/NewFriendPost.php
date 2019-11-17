<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewFriendPost extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $author;
    public $postId;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(object $author, $postId)
    {
        $this->author = $author;
        $this->postId = $postId;
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
            'author_name'   =>$this->author->name,
            'author_image'  =>$this->author->picture,
            'postId'        =>$this->postId
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'author_name'   =>$this->author->name,
            'author_image'  =>$this->author->picture,
            'postId'        =>$this->postId
        ]);
    }

}
