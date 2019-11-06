<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Arr;
use Auth;
use App\User;

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
            $this->notifications['user'] = $notifications->whereIn('type',[])->toArray();
            $this->notifications['system'] = $notifications->whereIn('type',['App\Notifications\NewProfilePicture','App\Notifications\UserFlagged'])->toArray();

            if (count($notifications['chat']) > 0) {
                $duplicateConvo = array();
                foreach ($notifications['chat'] as $chatNot) {
                    if (in_array($chatNot['data']['sender_id'],$duplicateConvo)) {
                        continue;
                    }else{
                        $sender = User::find($chatNot['data']['sender_id']);
                        $chatNot['senderName'] = $sender->name;
                        $chatNot['senderPicture'] = $sender->picture;
                        $this->notifications['chat'][] = $chatNot;
                        $duplicateConvo[] = $chatNot['data']['sender_id'];
                    }
                }
            }else{
                $this->notifications['chat'] = array();
            }

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
        $view->with('notifications', $this->notifications);
    }
}