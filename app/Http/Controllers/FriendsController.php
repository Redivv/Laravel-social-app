<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

use App\User;

class FriendsController extends Controller
{
    
    public function addFriend(User $user){
        $you = Auth::user();
        if($you->hasSentFriendRequestTo($user)){
            return redirect('searcher')->with(['status' => 'Już wysłane do '.$user->name]);
        }elseif($you->isFriendWith($user)){
            return redirect('searcher')->with(['status' => 'Już jesteście znajomymi z '.$user->name]);
        }elseif($user->hasSentFriendRequestTo($you)){
            return redirect('searcher')->with(['status' => $user->name.' już wysłał zaproszenie do ciebie']);
        }else{
            $you->befriend($user);
        }
        return redirect('searcher')->with(['status' => 'Wysłano zaproszenie do '.$user->name]);
    }
    public function acceptFriend(User $user){
        $you = Auth::user();
        $you->acceptFriendRequest($user);
    }
}
