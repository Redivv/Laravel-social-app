<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewMessage extends Notification implements ShouldQueue
{
    use Queueable;

    public $sender_id;
    public $body;
    public $image;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($id,$body,$image)
    {
        $this->sender_id = $id;
        $this->body = $body;
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
            'sender_id'     => $this->sender_id,
            'message_body'  => $this->body,
            'image_present' => $this->image,
        ];
    }
}
