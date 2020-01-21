<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

use App\Notifications\NewProfilePicture;
use App\Notifications\SystemNotification;

use Illuminate\Support\Facades\Notification;

use App\User;
use App\City;
use App\Post;

class ProfileController extends Controller
{
    //
    public function index(){
        
        $user = Auth::user();
        $tags = $user->tagNames();

        shuffle($tags);

        $profileNotifications = $user->notifications()->whereIn(
            'type',
            [
                'App\Notifications\SystemNotification',
                'App\Notifications\AdminWideInfo'
                ])->get();
        
        foreach ($profileNotifications as $profNot) {
            $profNot->delete();
        }

        $friends = count($user->getFriends());

        $posts = Post::where("user_id",Auth::id())->orderBy('created_at','desc')->take(5)->get();

        return view('profile')->withUser($user)->withTags($tags)->withFriends($friends)->withPosts($posts);
    }

    public function edit(){
        
        $user = Auth::user();

        $tags = $user->tagNames();
        return view('profileEdit')->with(compact('user'))->with(compact('tags'));
    }

    public function update(Request $request){

        $kek = $request->all();

        $user = Auth::user();

        // If request is valid
        $request->validate([
            'profilePicture'                =>  ['file','image','max:2000', 'nullable', 'mimes:jpeg,png,jpg,gif,svg'],
            'profileCity'                   =>  ['string','nullable','max:250'],
            'profileDesc'                   =>  ['string','nullable'],
            'profileRelationship'           =>  ['numeric', 'gte:0', 'lte:2','nullable'],
            'profileTags.*'                 =>  ['string','max:100','nullable']
        ]);

        //If there's a file
        if ($request->hasFile('profilePicture')) {
            //Change original name of the file
            $filename = hash_file('haval160,4',$request->profilePicture->getPathname()).'.'.$request->profilePicture->getClientOriginalExtension();
            $request->profilePicture->move(public_path('img/profile-pictures/'), $filename);
            copy(public_path('img/profile-pictures/').$filename,public_path('img/post-pictures/').$filename);
            $user->pending_picture = $filename;

            $admins = User::where('is_admin','=',1)->whereNotIn('id',[Auth::id()])->get();

            if($admins){
                Notification::send($admins, new NewProfilePicture($user->name,$filename));
            }
        }

        if (isset($request->profileCity)) {
            $city = City::firstOrCreate([
                'name'      => Str::title($request->profileCity),
                'name_slug' => Str::slug($request->profileCity)
            ]);

            $user->city_id = $city->id;
        }

        if (isset($request->profileRelationship)) {
            if ($request->profileRelationship == 3) {
                $user->relationship_status = null;
            }
            $user->relationship_status = $request->profileRelationship;
        }

        if (isset($request->profileDesc)) {
            $user->description = $request->profileDesc;
        }
        if (isset($request->profileTags)) {
            $user->untag();
            foreach ($request->profileTags as $tag) {
                $user->tag($tag);
            }
        }
        //Save changes in user profile

        if($user->update()){

            $request->session()->flash('message', __('profile.savedChanges'));
    
            return redirect(route('ProfileEdition'));
        }
    }

    public function visit(Request $request,User $user){
        
        if ($user->id == Auth::id()) {
            return redirect(url('user/profile'));
        }else{
            if(Auth::check() || $user->hidden_status == 0){

                $tags = $user->tagNames();
                $user->notify(new SystemNotification(__('nav.seenYourProfile'),'info','_user_profile','','','userSeenProfile'));

                $friends = count($user->getFriends());

                $posts = Post::where("user_id",$user->id)->get();

                return view('profile')->withUser($user)->withTags($tags)->withFriends($friends)->withPosts($posts);

            }elseif($user->hidden_status == 1){

                    $user->description=null;
                    $user->city_id=null;
                    $user->birth_year=null;
                    $user->relationship_status = null;
                    $user->notify(new SystemNotification(__('nav.seenYourProfile'),'info','_user_profile','','','userSeenProfile'));

                    $posts == null;

                    $tags = null;
                    $friends = null;

                    $request->session()->flash('guest', __('profile.logInToSee'));

                    return view('profile')->withUser($user)->withTags($tags)->withFriends($friends)->withPosts($posts);
            }else{
                return abort(404);
            }
        }
    }

    public function fetchContent(Request $request)
    {
        $request->validate([
            "requestedContent"  => [
                'string',
                Rule::in(['desc','tags','friends']),
            ],
            "userId" => [
                'numeric',
                'exists:users,id'
            ]
        ]);
        $requestedContent = $request->requestedContent;
        $user = User::find($request->userId);

        if (Auth::check() || $user->hidden_status == 0) {
            switch ($requestedContent) {
                case 'desc':
                    $desc = $user->description;
                    $html = view('partials.profile.descInfo')->withDesc($desc)->render();
                    break;
                case 'tags':
                    $tags = $user->tagNames();
                    $html = view('partials.profile.tagsInfo')->withTags($tags)->render();
                    break;
                case 'friends':
                    $friends = $user->getFriends();
                    $html = view('partials.profile.friendsInfo')->withFriends($friends)->render();
                    break;
            }
            return response()->json(['status' => 'success', 'html' => $html], 200);
        }elseif($user->hidden_status == 1){
            
        }else{
            return response()->json(['status' => 'error', 'message' => 'user not found'], 404);
        }
    }
}
