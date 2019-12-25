<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AdminMailInfo extends Notification implements ShouldQueue
{
    use Queueable;

    public $subject;
    public $desc;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $subject, string $desc)
    {
        $this->subject  = $subject;
        $this->desc     = $desc;
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
                ->subject(__($this->subject))
                ->greeting(__('admin.mailInfoGreet'))
                ->line(__($this->desc));
    }
}
