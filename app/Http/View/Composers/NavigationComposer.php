<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Arr;
use Auth;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\DB;
use Nahid\Talk\Facades\Talk;

class NavigationComposer
{   
    protected $notifications;

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users  
     * @return void
     */
    public function __construct()
    {
        if(Auth::check()){
            $notifications = Auth::user()->notifications()->get();
            //creates new variable that stores pending requests (the are entries in db 'friendships', that have Auth::user()'s id in recipient_id field).
            $friendRequests = Auth::user()->getFriendRequests();
            //counts number of pending friendships
            $frCount=$friendRequests->count();
            if($frCount==0){
                    //saves results to notifiation if no friend requests
            $this->notifications['FR'] = $friendRequests;
            }else{
                //gets info on users, that sent friend request
                for ($i=0; $i < $frCount ; $i++) { 
                    $friend_results[$i] = User::select('name','picture')
                        ->whereIn('id',[$friendRequests[$i]["sender_id"]])
                        ->get();
                        //$friend_results is some hella complexed data array, so we simplify it
                    $friend_results[$i] = $friend_results[$i][0];
                }
                $this->notifications['FR'] = $friend_results;
            }   

            // dd($friend_results);
            
                //saves results to notifiation
            $this->notifications['FRAmount'] = $frCount;
                //rest of the code :v
            $this->notifications['chat'] = $threads = Talk::user(Auth::id())->getInbox();
            $this->notifications['chatAmount'] = 0;

            $this->notifications['user'] = $notifications->whereIn('type',
            ['App\Notifications\NewFriendPost',
            'App\Notifications\NewAdminPost'
            ]);

            $this->notifications['userAmount'] = $notifications->whereIn('type',
            [
                'App\Notifications\NewFriendPost',
                'App\Notifications\NewAdminPost'
                ])->where('read_at',null)->count();

            $this->notifications['system'] = $notifications
                ->whereIn(
                    'type',
                    [
                        'App\Notifications\NewProfilePicture',
                        'App\Notifications\UserFlagged',
                        'App\Notifications\AcceptedPicture',
                        'App\Notifications\DeniedPicture',
                        'App\Notifications\AdminWideInfo'
                        ]);
            $this->notifications['systemAmount'] = $notifications
                ->whereIn(
                    'type',
                    [
                        'App\Notifications\NewProfilePicture',
                        'App\Notifications\UserFlagged',
                        'App\Notifications\AcceptedPicture',
                        'App\Notifications\DeniedPicture',
                        'App\Notifications\AdminWideInfo'
                        ])->where('read_at',null)->count();

            foreach ($this->notifications['chat'] as $chatNot) {
                if ($chatNot->thread->is_seen == 0 && $chatNot->thread->user_id != Auth::id()) {
                    $this->notifications['chatAmount']++;
                }
            }

        }else{
            $this->notifications = null;
        }
        // dd($this->notifications);
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {   
        // dd($this->notifications);
        // dd($this->notifications);
        $view->with('notifications', $this->notifications);
    }
}