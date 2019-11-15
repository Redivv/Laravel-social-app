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
use App\Notifications\NewFriendPost;
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
    public function index()
    {
        return view('home');
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
                # code...
                break;
            
            case 'systemNotifications':
                DB::table('notifications')
                    ->whereIn('type',[
                        'App\Notifications\NewProfilePicture',
                        'App\Notifications\UserFlagged',
                        'App\Notifications\AcceptedPicture',
                        'App\Notifications\DeniedPicture'
                    ])
                    ->where('notifiable_id',Auth::id())
                    ->where('read_at',null)
                    ->update(['read_at' => Carbon::now()->toDateTimeString()]);
                break;
        }
        return response()->json(['status' => 'success'], 200);
    }

    public function newPost(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'postDesc'       =>['required_without:postPicture','string','nullable'],
                'postPicture.*'  =>['required_without:postDesc','file','image','max:2000', 'mimes:jpeg,png,jpg,gif,svg'],
            ]);

            $postDesc = $request->input('postDesc');
            $pictures = $request->file('postPicture');
            $pictures_json = null;

            if($pictures){
                $pictures_json = array();
                foreach ($pictures as $picture) {
                    $imageName = hash_file('haval160,4',$picture->getPathname()).'.'.$picture->getClientOriginalExtension();
                    $picture->move(public_path('img/post-pictures'), $imageName);
                    $pictures_json[] = $imageName;
                }
                $pictures_json = json_encode($pictures_json);
            }

            $author = Auth::user();

            $post = new Post;
            $post->user_id = $author->id;
            $post->desc = $postDesc;
            $post->pictures = $pictures_json;

            
            if ($post->save()) {
                $friends = User::all();
                Notification::send($friends, new NewFriendPost($author));
            }

            return response()->json(['status' => 'success'], 200);
        }
    }

    public function deleteNotifications(Request $request)
    {

        if ($request->ajax()) {
            $request->validate([
                'type'    => [
                    'string',
                    Rule::in(['usNoNot','sysNoNot']),
                ]
            ]);
    
            switch ($request->type) {
                case 'usNoNot':
                    # code...
                    break;
                
                case 'sysNoNot':
                    DB::table('notifications')
                        ->whereIn('type',[
                            'App\Notifications\AcceptedPicture',
                            'App\Notifications\DeniedPicture'
                        ])
                        ->where('notifiable_id',Auth::id())
                        ->delete();
                    break;
            }
            return response()->json(['status' => 'success'], 200);
        }
    }
}
