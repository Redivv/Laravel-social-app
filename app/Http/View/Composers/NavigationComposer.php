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

            $this->notifications['chat'] = $threads = Talk::user(Auth::id())->getInbox();
            $this->notifications['chatAmount'] = 0;

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
        $view->with('notifications', $this->notifications);
    }
}