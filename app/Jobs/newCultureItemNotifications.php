<?php

namespace App\Jobs;

use App\cultureItem;
use App\Notifications\SystemNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;

class newCultureItemNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $newItem;
    protected $users;

    public $tries = 2;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Collection $users, cultureItem $newItem)
    {
        $this->users = $users;
        $this->newItem = $newItem;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->users as $user) {
            if (empty(array_intersect($user->tagNames(),$this->newItem->tagNames()))) {
                $user->notify(new SystemNotification(__('nav.newCultureItem',[],$user->locale),'success','_culture_',$this->newItem->name_slug,'', 'cultItem'));
            }else{
                $user->notify(new SystemNotification(__('nav.newCultureItemTag',[],$user->locale),'success','_culture_',$this->newItem->name_slug,'', 'cultTagItem'));
            }
        }
    }
}
