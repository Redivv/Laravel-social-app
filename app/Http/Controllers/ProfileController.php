<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

use App\Notifications\NewProfilePicture;
use App\Notifications\SystemNotification;

use Illuminate\Support\Facades\Notification;
use App\User;
use App\City;

class ProfileController extends Controller
{
    //
    public function index(){
        
        $user = Auth::user();
        $tags = $user->tagNames();

        $profileNotifications = $user->notifications()->whereIn(
            'type',
            [
                'App\Notifications\SystemNotification',
                'App\Notifications\AdminWideInfo'
                ])->get();
        
        foreach ($profileNotifications as $profNot) {
            $profNot->delete();
        }

        $friends = $user->getFriends();

        return view('profile')->with(compact('user'))->with(compact('tags'))->with(compact('friends'));
    }

    public function edit(){
        
        $user = Auth::user();

        $tags = $user->tagNames();
        return view('profileEdit')->with(compact('user'))->with(compact('tags'));
    }

    public function update(){

        $user = Auth::user();

        // If request is valid
        request()->validate([
            'photo'             =>  'mimes:jpeg,png,jpg,gif|max:2048',
            'city'              =>  ['string','nullable','max:250'],
            'description'       =>  ['string','nullable','max:500'],
            'status'            =>  ['numeric', 'gte:0', 'lte:2' ],
            'relations'         =>  ['boolean']
        ]);
        //If there's a file
        if (request()->hasFile('photo')) {
            //Change original name of the file
            $filename = hash_file('haval160,4',request('photo')->getPathname()).'.'.request('photo')->getClientOriginalExtension();
            request('photo')->move(public_path('img/profile-pictures/'), $filename);
            copy(public_path('img/profile-pictures/').$filename,public_path('img/post-pictures/').$filename);
            $user->pending_picture = $filename;

            $admins = User::where('is_admin','=',1)->whereNotIn('id',[Auth::id()])->get();

            if($admins){
                Notification::send($admins, new NewProfilePicture($user->name,$filename));
            }
        }
        
        $city = City::firstOrCreate([
            'name'      => Str::title(request('city')),
            'name_slug' => Str::slug(request('city'))
        ]);

        $user->city_id = $city->id;
        $user->relationship_status = request('relations');
        $user->description = request('description');
        //Save changes in user profile
        $user->update();

        return redirect(route('ProfileView'))->with(['status' => __('profile.updated')]);
    }

    public function visit(Request $request,User $user){
        
        if ($user->id == Auth::id()) {
            return redirect(url('user/profile'));
        }else{
            if(Auth::check()){

                $tags = $user->tagNames();
                $user->notify(new SystemNotification(__('nav.seenYourProfile'),'info','_user_profile','','','userSeenProfile'));

                $friends = $user->getFriends();

                return view('profile')->with(compact('user'))->with(compact('tags'))->with(compact('friends'));

            }else{
                if($user->hidden_status == 0){

                    $user->notify(new SystemNotification(__('nav.seenYourProfile'),'info','_user_profile','','','userSeenProfile'));
                    $tags = $user->tagNames();

                    $friends = $user->getFriends();

                    return view('profile')->with(compact('user'))->with(compact('tags'))->with(compact('friends'));

                }elseif($user->hidden_status == 1){

                    $user->description=null;
                    $user->city_id=null;
                    $user->birth_year=null;
                    $user->notify(new SystemNotification(__('nav.seenYourProfile'),'info','_user_profile','','','userSeenProfile'));

                    $request->session()->flash('guest', __('profile.logInToSee'));

                    return view('profile')->with(compact('user'));
                }else{
                    return abort(404);
                }
            }
        }
    }
}
