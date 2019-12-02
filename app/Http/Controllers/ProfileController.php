<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

use App\Notifications\NewProfilePicture;

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
        return view('profile')->with(compact('user'))->with(compact('tags'));
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
            'photo'         =>  'mimes:jpeg,png,jpg,gif|max:2048',
            'city'          =>  ['string','nullable','max:250'],
            'description'   => ['string','nullable','max:500'],
            'status'        =>  ['numeric', 'gte:0', 'lte:2' ],
            'relations'        =>  ['numeric', 'gte:0', 'lte:1' ]
        ]);
        //If there's a file
        if (request()->hasFile('photo')) {
            //Change original name of the file
            $filename = hash_file('haval160,4',request('photo')->getPathname()).'.'.request('photo')->getClientOriginalExtension();
            request('photo')->move(public_path('img/profile-pictures/'), $filename);
            copy(public_path('img/profile-pictures/').$filename,public_path('img/post-pictures/').$filename);
            $user->pending_picture = $filename;

            $admins = User::where('is_admin','=',1)->get();

            if($admins){
                Notification::send($admins, new NewProfilePicture($user->name,$filename));
            }
        }
        
        $city = City::firstOrCreate([
            'name'      => Str::title(request('city')),
            'name_slug' => Str::slug(request('city'))
        ]);

        $user->city_id = $city->id;
        $user->hidden_status = request('status');
        $user->relationship_status = request('relations');
        $user->description = request('description');
        //Save changes in user profile
        $user->update();

        return redirect(route('ProfileView'))->with(['status' => __('profile.updated')]);
    }

    public function visit(User $user){
        $user->email='Private data';  //Seeing other's email is impossible (safety reasons);
        if(Auth::check()){ //If user's logged in, he can explore any profile
            $tags = $user->tagNames();
            return view('profile')->with(compact('user'))->with(compact('tags'));
        }else{
            if($user->hidden_status == 0){ //Guests can freely see profile of any person with hidden_status==0;
                //Show user's profile 
                $tags = $user->tagNames();
                return view('profile')->with(compact('user'))->with(compact('tags'));
            }elseif($user->hidden_status == 1){ // Guests have restricted access to profile with hidden_status==1;
                $user->description='err0000';
                $user->city_id=null;
                $user->birth_year='err0000';
                return view('profile')->with(compact('user'))->with(['status' => 'Nie jesteś zalogowany']);
            }else{ //Guests cannot find anyone with hidden_status==2, if they even try they get redirrected back to searcher (or mb register/login, dunno yet);
                return redirect('searcher')->with(['status' => 'Nie można wyświetlić profilu użytkownika '.$user->name.' jako gość.']);
            }
        }
        
        
    }
}
