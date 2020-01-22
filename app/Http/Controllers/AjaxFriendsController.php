<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

use App\Notifications\FriendRequestSend;
use App\Notifications\FriendRequestAccepted;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

use App\User;
use App\Post;

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
            
            $yourFriends = $you->getFriends();
            $allFriends = $yourFriends->merge($user->getFriends());

            $you->acceptFriendRequest($user);

            $user->notify(new FriendRequestAccepted($you));

            $post = new Post;
            $post->user_id      = $you->id;
            $post->desc         = __('activityWall.newFriend', ['user1' => $you->name, 'user2' => $user->name]);
            $post->is_public    = false;
            
            
            copy(public_path('img/profile-pictures/').$you->picture,public_path('img/post-pictures/').$you->picture);
            copy(public_path('img/profile-pictures/').$user->picture,public_path('img/post-pictures/').$user->picture);

            $post->pictures     = json_encode([$you->picture,$user->picture]);


            if ($post->save()) {
                Notification::send($allFriends, new UserNotification($you, '_user_home_post_',$post->id, '', __('nav.userNot5',['user' => $user->name]), 'newPost'.$post->id));
            }

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
