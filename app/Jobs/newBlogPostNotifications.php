<?php

namespace App\Jobs;

use App\blogPost;
use App\Notifications\SystemNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;

class newBlogPostNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $newPost;
    protected $users;

    public $tries = 2;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Collection $users, blogPost $newPost)
    {
        $this->users = $users;
        $this->newPost = $newPost;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->users as $user) {
            if (empty(array_intersect($user->tagNames(),$this->newPost->tagNames()))) {
                $user->notify(new SystemNotification(__('nav.newBlogPost',[],$user->locale),'success','_blog_',$this->newPost->name_slug,'', 'blogPost'.$this->newPost->id));
            }else{
                $user->notify(new SystemNotification(__('nav.newBlogPostTag',[],$user->locale),'success','_blog_',$this->newPost->name_slug,'', 'blogTagPost'.$this->newPost->id));
            }
        }
    }
}
