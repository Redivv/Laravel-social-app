<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserDeleted extends Notification
{
    use Queueable;

    public $user_name;
    public $locale;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $user_name, string $locale)
    {
        $this->user_name = $user_name;
        $this->locale    = $locale;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                ->subject(__('admin.deletedUser-subject',[],$this->locale))
                ->greeting(__('admin.deletedUser-greet',['user' => $this->user_name],$this->locale))
                ->line(__('admin.deletedUser-message',[],$this->locale))
                ->line(__('admin.deletedUser-messageInfo',[],$this->locale));
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
            //
        ];
    }
}
