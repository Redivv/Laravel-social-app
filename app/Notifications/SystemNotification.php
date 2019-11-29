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
    public $contentId;
    public $contentAnchor;
    public $action;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $message,string $color,string $link, $contentId, ?string $contentAnchor = '', string $action)
    {
        $this->message          = $message;
        $this->color            = $color;
        $this->link             = $link;
        $this->contentId        = $contentId;
        $this->contentAnchor    = $contentAnchor;
        $this->action           = $action;
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
            'link'          => $this->link,
            'contentId'     => $this->contentId,
            'contentAnchor' => $this->contentAnchor,
            'action'        => $this->action
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message'       => $this->message,
            'color'         => $this->color,
            'link'          => $this->link,
            'contentId'     => $this->contentId,
            'contentAnchor' => $this->contentAnchor,
            'action'        => $this->action
        ]);
    }
}
