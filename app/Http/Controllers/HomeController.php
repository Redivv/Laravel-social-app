<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Nahid\Talk\Facades\Talk;
use Auth;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Notifications\UserFlagged;
use App\Post;
use App\User;
use App\Notifications\SystemNotification;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('verified');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        
        $posts = Post::orderBy('created_at', 'desc')->take(5)->get();

        if ($request->ajax()) {
            $html = view('partials.friendsWallPosts')->withPosts($posts)->render();
            return response()->json(['status' => 'success', 'html' => $html], 200);
        }

        $userNotifications = Auth::user()->notifications()->whereIn(
            'type',
            [
                'App\Notifications\UserNotification',
                'App\Notifications\NewAdminPost',
                ])->get();
        
        foreach ($userNotifications as $usNot) {
            $usNot->delete();
        }

        //Friendships 
        $you=Auth::user();
        //gets your friends
        $friends=$you->getFriends();

        return view('home')->withPosts($posts)->withFriends($friends);
    }

    public function viewPost(Post $post)
    {
        return view('viewSinglePost')->withPost($post);
    }

    public function getMorePosts(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'pagiTime'   => 'numeric'
            ]);
            
            $stopPagi = false;
            $posts = Post::orderBy('created_at', 'desc')->skip(5*$request->pagiTime)->take(5)->get();
            if (count($posts) < 5) {
                $stopPagi = true;
            }

            $html = view('partials.friendsWallPosts')->withPosts($posts)->render();

            return response()->json(['status' => 'success', 'html' => $html, 'stopPagi' => $stopPagi], 200);
        }
    }

    public function report(Request $request)
    {
        $request->validate([
            'userName'    => ['required','string','exists:users,name'],  
            'reason'      => ['required','string','max:255']
        ]);

        $admins = User::where('is_admin','=',1)->get();

        if($admins){
            Notification::send($admins, new UserFlagged($request->userName,$request->reason));
        }

        return response()->json(['status' => 'success'], 200);
    }

    public function readNotifications(Request $request)
    {
        $request->validate([
            'type'    => [
                'string',
                Rule::in(['userNotifications','systemNotifications']),
            ]
        ]);

        switch ($request->type) {
            case 'userNotifications':
                DB::table('notifications')
                    ->whereIn('type',[
                        'App\Notifications\UserNotification',
                        'App\Notifications\NewAdminPost',
                    ])
                    ->where('notifiable_id',Auth::id())
                    ->where('read_at',null)
                    ->update(['read_at' => Carbon::now()->toDateTimeString()]);
                break;
            
            case 'systemNotifications':
                DB::table('notifications')
                    ->whereIn('type',[
                        'App\Notifications\NewProfilePicture',
                        'App\Notifications\UserFlagged',
                        'App\Notifications\SystemNotification',
                    ])
                    ->where('notifiable_id',Auth::id())
                    ->where('read_at',null)
                    ->update(['read_at' => Carbon::now()->toDateTimeString()]);
                break;
        }
        return response()->json(['status' => 'success'], 200);
    }
    
    public function getPost(Request $request, Post $post)
    {
        if ($request->ajax()) {
            $html = view('partials.postEditForm')->withPost($post)->render();
            return response()->json(['status' => 'success', 'html' => $html], 200);
        }
    }

    public function newPost(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'postDesc'       =>['required_without:postPicture','string','nullable'],
                'postPicture.*'  =>['required_without:postDesc','file','image','max:2000', 'mimes:jpeg,png,jpg,gif,svg'],
                'taggedUser.*'   =>['exists:users,id','distinct']
            ]);

            $postDesc           = $request->input('postDesc');
            $pictures           = $request->file('postPicture');
            $taggedUsers        = $request->taggedUser;
            $pictures_json      = null;
            $taggedUsers_json   = null;

            if($pictures){
                $pictures_json = array();
                foreach ($pictures as $picture) {
                    $imageName = hash_file('haval160,4',$picture->getPathname()).'.'.$picture->getClientOriginalExtension();
                    $picture->move(public_path('img/post-pictures'), $imageName);
                    $pictures_json[] = $imageName;
                }
                $pictures_json = json_encode($pictures_json);
            }

            if ($taggedUsers) {
                $taggedUsers_json = array();
                foreach ($taggedUsers as $tagged) {
                    $user = User::find($tagged);
                    $taggedUsers_json[] = $user->name;
                }
                $taggedUsers_json = json_encode($taggedUsers_json);
            }

            $author = Auth::user();

            $post = new Post;

            $post->user_id      = $author->id;
            $post->desc         = $postDesc;
            $post->pictures     = $pictures_json;
            $post->tagged_users = $taggedUsers_json;

            
            if ($post->save()) {
                $posts = [$post];
                $html = view('partials.friendsWallPosts')->withPosts($posts)->render();
                $friends = User::whereNotIn('id',[Auth::id()])->get();
                Notification::send($friends, new UserNotification($author, '_user_home_post_',$post->id, '', __('nav.userNot2'), 'newPost'));
                return response()->json(['status' => 'success', 'html' => $html], 200);
            }

            return response()->json(['status' => 'error'], 400);
        }
    }

    public function editPost(Request $request)
    {
        if ($request->ajax()) {
            $kek = $request->all();
            $request->validate([
                'postDesc'       =>['required_without:postPicture','string','nullable'],
                'editPicture.*'  =>['required_without:postDesc', 'nullable','file','image','max:2000', 'mimes:jpeg,png,jpg,gif,svg'],
                'postId'         =>['exists:posts,id'],
                'noPicture'      =>['string','nullable'],
                'taggedUser.*'   =>['exists:users,id','distinct'],
                'noTags'      =>['string','nullable']
            ]);
            $postDesc = $request->input('postDesc');
            $pictures = $request->file('editPicture');
            $taggedUsers        = $request->taggedUser;
            $pictures_json      = null;
            $taggedUsers_json   = null;

            $post = Post::where('id',$request->postId)->where('user_id',Auth::id())->first();
            $post->desc = $postDesc;

            if($pictures){
                $pictures_json = array();
                foreach ($pictures as $picture) {
                    $imageName = hash_file('haval160,4',$picture->getPathname()).'.'.$picture->getClientOriginalExtension();
                    $picture->move(public_path('img/post-pictures'), $imageName);
                    $pictures_json[] = $imageName;
                }
                $pictures_json = json_encode($pictures_json);
                $post->pictures = $pictures_json;
            }elseif(isset($request->noPicture)) {
                $post->pictures = null;
            }

            if ($taggedUsers) {
                $taggedUsers_json = array();
                foreach ($taggedUsers as $tagged) {
                    $user = User::find($tagged);
                    $taggedUsers_json[] = $user->name;
                }
                $taggedUsers_json = json_encode($taggedUsers_json);
                $post->tagged_users = $taggedUsers_json;
            }elseif(isset($request->noTags)){
                $post->tagged_users = null;
            }

            
            if ($post->update()) {
                $posts = [$post];
                $html = view('partials.friendsWallPosts')->withPosts($posts)->render();
                return response()->json(['status' => 'success', 'html' => $html], 200);
            }

            return response()->json(['status' => 'error'], 400);
        }
    }

    public function deletePost(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'id'    => ['required','exists:posts']
            ]);
            
            if(Post::where('id',$request->id)->where('user_id',Auth::id())->delete()){
                
                DB::table('likeable_like_counters')->where('likeable_id',$request->id)->delete();
                DB::table('likeable_likes')->where('likeable_id',$request->id)->delete();

                return response()->json(['status' => 'success'], 200);
            }
            return response()->json(['status' => 'error'], 400);
        }
    }

    public function deleteNotifications(Request $request)
    {

        if ($request->ajax()) {
            $request->validate([
                'type'    => [
                    'string',
                    Rule::in(['sysNoNot']),
                ]
            ]);
    
            switch ($request->type) {
                
                case 'sysNoNot':
                    DB::table('notifications')
                        ->whereIn('type',[
                            'App\Notifications\SystemNotification',
                            'App\Notifications\AdminWideInfo'
                        ])
                        ->where('notifiable_id',Auth::id())
                        ->delete();
                    break;
            }
            return response()->json(['status' => 'success'], 200);
        }
    }

    

    public function likePost(Request $request)
    {
        if($request->ajax()){
            $request->validate([
                'postId' => 'exists:posts,id'
            ]);

            $post = Post::find($request->postId);

            if ($post->liked()) {
                $post->unlike();
            }else{
                $post->like();

                if ($post->user_id != Auth::id()) {
                    $post->user->notify(new SystemNotification(__('nav.likePostNot'),'info','_user_home_post_',$post->id, '', 'likePost'));
                }
            }

            return response()->json(['status' => 'success'], 200);
        }
    }

    public function checkUser(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'userName'    => ['string','exists:users,name']
            ]);
            $user = User::where('name',$request->userName)->first();
            return response()->json(['status' => 'success', 'userId' => $user->id], 200);
        }
    }

    public function getTagged(Request $request, Post $post)
    {
        if ($request->ajax()) {

            if($taggedUsers = json_decode($post->tagged_users)){
                $users = User::whereIn('name',$taggedUsers)->get();

                if (count($users) > 0) {
                    $taggedUsersHtml = view('partials.wallTaggedUsers')->withTaggedUsers($users)->render();
                }else{
                    return response()->json(['status' => 'error'], 400);
                }

                return response()->json(['status' => 'success', 'html' => $taggedUsersHtml], 200);
            }else{
                return response()->json(['status' => 'success', 'html' => ''], 200);
            }
        }
    }
}
