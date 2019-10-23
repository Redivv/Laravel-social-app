<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Events\UserOnline;
use App\Events\UserOffline;

class UserStatusController extends Controller
{
    
    public function setOnline(User $user)
    {
        $user->status = 'online';
        $user->update();

        broadcast(new UserOnline($user));
    }

    public function setOffline(User $user)
    {
        $user->status = 'offline';
        $user->update();

        broadcast(new UserOffline($user));
    }
}
