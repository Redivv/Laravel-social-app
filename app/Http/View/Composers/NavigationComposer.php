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
    protected $systemDuplicatesAmount;

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
            ['App\Notifications\UserNotification',
            'App\Notifications\NewAdminPost'
            ]);

            $this->notifications['userAmount'] = $notifications->whereIn('type',
            [
                'App\Notifications\UserNotification',
                'App\Notifications\NewAdminPost'
                ])->where('read_at',null)->count();

            $this->notifications['system'] = $notifications
                ->whereIn(
                    'type',
                    [
                        'App\Notifications\NewProfilePicture',
                        'App\Notifications\UserFlagged',
                        'App\Notifications\AdminWideInfo',
                        'App\Notifications\SystemNotification',
                        ]);
            $this->notifications['systemAmount'] = $notifications
                ->whereIn(
                    'type',
                    [
                        'App\Notifications\NewProfilePicture',
                        'App\Notifications\UserFlagged',
                        'App\Notifications\AdminWideInfo',
                        'App\Notifications\SystemNotification',
                        ])->where('read_at',null)->count();

            foreach ($this->notifications['chat'] as $chatNot) {
                if ($chatNot->thread->is_seen == 0 && $chatNot->thread->user_id != Auth::id()) {
                    $this->notifications['chatAmount']++;
                }
            }

            $systemDuplicatesAmount = array();
            $duplicateNot = array();
            $duplicateCount = 1;

            foreach ($this->notifications['system'] as $key => $sysNot) {
                if ($sysNot->type == 'App\Notifications\SystemNotification') {
                    if (in_array($sysNot->data['action'].$sysNot->data['contentId'],$duplicateNot)) {
                        $duplicateCount++;
                        if ($sysNot->type != "App\Notifications\AdminWideInfo") {
                            unset($this->notifications["system"][$key]);
                        }
                    }else{
                        $duplicateNot[] = $sysNot->data['action'].$sysNot->data['contentId'];
                        $systemDuplicatesAmount[] = $duplicateCount;
                        $duplicateCount = 1;
                    }
                }else{
                    continue;
                }
            }
            $systemDuplicatesAmount[] = $duplicateCount;
            unset($systemDuplicatesAmount[0]);
            $this->systemDuplicatesAmount = array_values($systemDuplicatesAmount);

        }else{
            $this->notifications = null;
        }
        $kek = $this->notifications;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {  
        $view->with('notifications')->withNotifications($this->notifications)->withSystemDuplicates($this->systemDuplicatesAmount);
    }
}