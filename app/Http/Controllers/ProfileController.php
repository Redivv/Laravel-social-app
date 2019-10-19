<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\City;

class ProfileController extends Controller
{
    //
    public function index(){
        if (Auth::check()) {
            // The user is logged in...
            $user = Auth::user();
        }
        $tags = $user->tagNames();
        return view('profile')->with(compact('user'))->with(compact('tags'));
    }

    public function edit(){

        if (Auth::check()) {
            // The user is logged in...
            $user = Auth::user();
        }
        $tags = $user->tagNames();
        return view('profileEdit')->with(compact('user'))->with(compact('tags'));
    }

    public function update(){

        $user = Auth::user();

        // If request is valid
        request()->validate([
            'photo'     =>  'mimes:jpeg,png,jpg,gif|max:2048',
            'city'      =>  ['string','nullable','max:250'],
            'description'   => ['string','nullable','max:500'],
            'status'    =>  ['numeric', 'gte:0', 'lte:2' ]
        ]);
        //If there's a file
        if (request()->hasFile('photo')) {
            //Change original name of the file
            $filename = hash_file('haval160,4',request('photo')->getPathname()).'.'.request('photo')->getClientOriginalExtension();
            request('photo')->move(public_path('img/profile-pictures/'), $filename);
            $user->picture = $filename;
        }
        
        $city = City::firstOrCreate([
            'name'      => Str::title(request('city')),
            'name_slug' => Str::slug(request('city'))
        ]);

        $user->city_id = $city->id;
        $user->hidden_status = request('status');
        $user->description = request('description');
        //Save changes in user profile
        $user->update();

        // return request('photo');
        return redirect('profile')->with(['status' => 'Profile updated successfully.']);
    }

    public function visit(User $user){
        if(Auth::check()){
            //Show user's profile 
            $tags = $user->tagNames();
            return view('profile')->with(compact('user'))->with(compact('tags'));
        }else{
            if($user->hidden_status == 0){
                //Show user's profile 
                $tags = $user->tagNames();
                return view('profile')->with(compact('user'))->with(compact('tags'));
            }elseif($user->hidden_status == 1){
                return "prawie ukryty!";
            }else{
                return redirect('searcher')->with(['status' => 'Nie można wyświetlić profilu tego użytkownika']);
            }
        }
        
        
    }
}
