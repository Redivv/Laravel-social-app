<?php

namespace App\Jobs;

use App\blogPost;
use App\Event;
use App\Notifications\SystemNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;

class newEventPostNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $newEvent;
    protected $users;

    public $tries = 2;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Collection $users, Event $newEvent)
    {
        $this->users = $users;
        $this->newEvent = $newEvent;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->users as $user) {
            $user->notify(new SystemNotification(__('nav.newEvent',[],$user->locale),'success','_blog','','', 'blogEvent'.$this->newEvent->id));
        }
    }
}
