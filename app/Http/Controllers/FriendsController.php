<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

use App\User;

class FriendsController extends Controller
{
    public function acceptFriend(User $user){
        $you = Auth::user();
        $you->acceptFriendRequest($user);
        return redirect('user/home')->with(['status' => 'Zaakceptowano zaproszenie '.$user->name]);
    }
    public function denyRequest(User $user){
        $you = Auth::user();
        $you->denyFriendRequest($user);
        return redirect('user/home')->with(['status' => 'Odrzucono zaproszenie '.$user->name]);
    }

}
