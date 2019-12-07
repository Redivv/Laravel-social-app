<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

use App\Notifications\FriendRequestSend;
use App\Notifications\FriendRequestAccepted;

use App\User;

class AjaxFriendsController extends Controller
{
    public function addFriend(Request $request,User $user){
        if($request->ajax()){
            $you=Auth::user();
            if($you->hasSentFriendRequestTo($user)){
                return response()->json(['status' => 'repeat', 'message' => 'alreadySent'],400);
            }elseif($you->isFriendWith($user)){
                return response()->json(['status' => 'repeat', 'message' => 'alreadyFriends'],400);
            }elseif($user->hasSentFriendRequestTo($you)){
                return response()->json(['status' => 'repeat', 'message' => 'gotRequestFromHim'],400);
            }else{
                $you->befriend($user);
                $user->notify(new FriendRequestSend($you));
            }
            return response()->json(['status' => 'success'],200);
        }
    }
    public function deleteFriend(Request $request,User $user){
        if($request->ajax()){
            if(Auth::user()->isFriendWith($user)){
                Auth::user()->unfriend($user);
                return response()->json(['status' => 'success'],200);
            }
        }
    }
    public function acceptFriend(Request $request,User $user){
        if($request->ajax()){
            $you = Auth::user();
            $you->acceptFriendRequest($user);
            $user->notify(new FriendRequestAccepted($you));
            return response()->json(['status' => 'success'],200);
        }
    }
    public function denyFriend(Request $request,User $user){
        if($request->ajax()){
            $you = Auth::user();
            $you->denyFriendRequest($user);
            return response()->json(['status' => 'success'],200);
        }
    }
}
