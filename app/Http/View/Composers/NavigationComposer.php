<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Auth;

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
            $this->notifications = Auth::user()->notifications()->where('type', 'App\Notifications\NewMessage')->get();
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