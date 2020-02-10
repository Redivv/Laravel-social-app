<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\User;
use App\Post;

use Illuminate\Support\Facades\Notification;

use App\Notifications\UserNotification;
use App\Notifications\SystemNotification;

class HandleProfilePictureTicket implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $decision;
    protected $backupImage;

    public $tries = 2;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user,string $decision, string $backupImage)
    {
        $this->user = $user;
        $this->decision = $decision;
        $this->backupImage = $backupImage;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        switch ($this->decision) {
            case 'accept':
                $lastPicture = $this->user->picture;
                $this->user->picture = $this->user->pending_picture;
                
                if (!$this->user->picture) {
                    $this->user->picture = $this->backupImage;
                }

                $this->user->pending_picture = null;
                $this->user->update();
                $this->user->notify(new SystemNotification(__('nav.pictureOk'),'success','_user_profile','','','userPictureOk'));
                
                if ($lastPicture !== "default-picture.png") {
                    unlink(public_path('img/profile-pictures/'.$lastPicture));
                }

                $post = new Post;
                $post->user_id      = $this->user->id;
                $post->is_public    = false;
                $post->pictures     = json_encode([$this->user->picture]);
                $post->type         = "newPicture";
                $post->tagged_users = json_encode([$this->user->name]);

                copy(public_path('img/profile-pictures/').$this->user->picture,public_path('img/post-pictures/').$this->user->picture);


                if ($post->save()) {
                    Notification::send($this->user->getFriends(), new UserNotification($this->user, '_user_home_post_',$post->id, '', __('nav.userNot3'), 'newPost'.$post->id));
                }

                break;
            case 'refuse':
                unlink(public_path('img/profile-pictures/'.$this->user->pending_picture));
                $this->user->pending_picture = null;
                $this->user->update();
                $this->user->notify(new SystemNotification(__('nav.pictureDeny'),'danger','_user_profile','','','userPictureNo'));
                break;
        }
    }
}
