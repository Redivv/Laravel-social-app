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
            }
            $systemDuplicatesAmount[] = $duplicateCount;
            unset($systemDuplicatesAmount[0]);
            $this->systemDuplicatesAmount = array_values($systemDuplicatesAmount);

        }else{
            $this->notifications = null;
        }
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