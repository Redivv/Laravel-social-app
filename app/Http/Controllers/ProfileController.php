<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;

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
            'photo'     =>  'mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        //If there's a file
        if (request()->hasFile('photo')) {
            //Change original name of the file
            $filename = hash_file('haval160,4',request('photo')->getPathname()).'.'.request('photo')->getClientOriginalExtension();
            request('photo')->move(public_path('img/profile-pictures/'), $filename);
            $user->picture = $filename;
        }
        $user->city = request('city');
        $user->description = request('description');
        //Save changes in user profile
        $user->update();

        // return request('photo');
        return redirect('profile')->with(['status' => 'Profile updated successfully.']);
    }

    public function visit(User $user){

        //Show user's profile 
        return view('profile', compact('user'));
    }
}
