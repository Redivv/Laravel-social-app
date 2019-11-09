<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Arr;
use Auth;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\DB;

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

            $notifications['chat'] = $notifications->whereIn('type',['App\Notifications\NewMessage'])->toArray();
            $notificationsChatAmount = 0;

            $this->notifications['user'] = $notifications->whereIn('type',[])->toArray();
            $this->notifications['userAmount'] = $notifications->whereIn('type',[])->where('read_at',null)->count();

            $this->notifications['system'] = $notifications
                ->whereIn(
                    'type',
                    [
                        'App\Notifications\NewProfilePicture',
                        'App\Notifications\UserFlagged',
                        'App\Notifications\AcceptedPicture',
                        'App\Notifications\DeniedPicture'
                        ])->toArray();
            $this->notifications['systemAmount'] = $notifications
                ->whereIn(
                    'type',
                    [
                        'App\Notifications\NewProfilePicture',
                        'App\Notifications\UserFlagged',
                        'App\Notifications\AcceptedPicture',
                        'App\Notifications\DeniedPicture'
                        ])->where('read_at',null)->count();

            if (count($notifications['chat']) > 0) {
                $duplicateConvo = array();
                foreach ($notifications['chat'] as $chatNot) {
                    if (in_array($chatNot['data']['sender_id'],$duplicateConvo)) {
                        continue;
                    }else{
                        $sender = User::find($chatNot['data']['sender_id']);
                        if(!$sender){
                            DB::table('notifications')->where('id',$chatNot['id'])->delete();
                            continue;
                        }else{
                            $carbon = new Carbon($chatNot['created_at'],'Europe/Warsaw');
                            $chatNot['created_at'] = $carbon->diffForHumans();
                            $chatNot['senderName'] = $sender->name;
                            $chatNot['senderPicture'] = $sender->picture;
                            $this->notifications['chat'][] = $chatNot;
                            $duplicateConvo[] = $chatNot['data']['sender_id'];
                            if ($chatNot['read_at'] == null) {
                                $notificationsChatAmount++;
                            }
                        }
                    }
                }
                $this->notifications['chatAmount'] = $notificationsChatAmount;
            }else{
                $this->notifications['chat'] = array();
                $this->notifications['chatAmount'] = $notificationsChatAmount;
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
        $view->with('notifications', $this->notifications);
    }
}