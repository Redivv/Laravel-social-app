<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

use App\Notifications\FriendRequestSend;
use App\Notifications\FriendRequestAccepted;
use App\Notifications\UserNotification;
use App\Notifications\SystemNotification;
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

            if ($you->isFriendWith($user)) {
                if ($user->relationship_status == 4 && $user->partner_id === null) {

                    $you->partner_id = $user->id;
                    $you->relationship_status = 1;

                    $user->partner_id = $you->id;
                    $user->relationship_status = 1;

                    if ($you->update() && $user->update()) {

                        $partnerRequests = $you->notifications()->whereIn('type',
                        [
                            'App\Notifications\PendingPartnerRequest',
                        ])->get();
    
                        foreach ($partnerRequests as $request) {
                            $request->delete();
                        }

                        $user->notify(new SystemNotification(__('nav.acceptedPartner', ['user' => $you->name],$user->locale),'success','_user_profile_',$you->name,'','userAcceptedPartner'));
            
                        $yourFriends = $you->getFriends()->reject(function ($friend,$key) use ($user){
                            return $friend->id == $user->id;
                        });

                        $userFriends = $user->getFriends()->reject(function($friend,$key) use ($you){
                            return $friend->id == $you->id;
                        });

                        $post = new Post;
                        $post->user_id      = $you->id;
                        $post->is_public    = false;
                        $post->type         = "newPartner";
                        $post->tagged_users = json_encode([$you->name,$user->name]);

                        $post2 = new Post;
                        $post2->user_id      = $user->id;
                        $post2->is_public    = false;
                        $post2->type         = "newPartner";
                        $post2->tagged_users = json_encode([$you->name,$user->name]);
        
                        if ($post->save()) {
                            Notification::send($yourFriends, new UserNotification($you, '_user_home_post_',$post->id, '', __('nav.userNot6',['user' => $user->name]), 'newPost'.$post->id));
                        }

                        if ($post2->save()) {
                            Notification::send($userFriends, new UserNotification($user, '_user_home_post_',$post2->id, '', __('nav.userNot6',['user' => $you->name]), 'newPost'.$post2->id));
                        }
                    }
                }
            }else{
            
                $yourFriends = $you->getFriends()->reject(function ($friend,$key) use ($user){
                    return $friend->id == $user->id;
                });

                $userFriends = $user->getFriends()->reject(function($friend,$key) use ($you){
                    return $friend->id == $you->id;
                });

                $you->acceptFriendRequest($user);

                $user->notify(new FriendRequestAccepted($you));

                $post = new Post;
                $post->user_id      = $you->id;
                $post->is_public    = false;
                $post->type         = "newFriend";
                $post->tagged_users = json_encode([$you->name,$user->name]);

                $post2 = new Post;
                $post2->user_id      = $user->id;
                $post2->is_public    = false;
                $post2->type         = "newFriend";
                $post2->tagged_users = json_encode([$you->name,$user->name]);

                if ($post->save()) {
                    Notification::send($yourFriends, new UserNotification($you, '_user_home_post_',$post->id, '', __('nav.userNot5',['user' => $user->name]), 'newPost'.$post->id));
                }

                if ($post2->save()) {
                    Notification::send($userFriends, new UserNotification($user, '_user_home_post_',$post2->id, '', __('nav.userNot5',['user' => $you->name]), 'newPost'.$post2->id));
                }

                return response()->json(['status' => 'success'],200);
            }
        }
    }

    public function denyFriend(Request $request,User $user){
        if($request->ajax()){
            $you = Auth::user();
            if ($you->isFriendWith($user)) {
                $user->partner_id = null;
                $user->relationship_status = 0;

                $partnerRequests = $you->notifications()->whereIn('type',
                    [
                        'App\Notifications\PendingPartnerRequest',
                    ])->get();

                foreach ($partnerRequests as $request) {
                    if ($request->data['sender_id'] == $user->id) {
                        $request->delete();
                    }
                }

                if ($user->update()) {
                    $user->notify(new SystemNotification(__('nav.deniedPartner', ['user' => $you->name], $user->locale),'danger','_user_profile_',$you->name,'','userDeniedPartner'));
                }
            }else{
                $you = Auth::user();
                $you->denyFriendRequest($user);
                return response()->json(['status' => 'success'],200);
            }


        }
    }
}
