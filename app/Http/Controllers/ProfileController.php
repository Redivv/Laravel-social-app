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
        return view('profile', compact('user'));
    }

    public function edit(){

        if (Auth::check()) {
            // The user is logged in...
            $user = Auth::user();
        }
        return view('profileEdit', compact('user'));
    }

    public function update(){

        $user = Auth::user();
        //If there's a file
        if (request()->hasFile('photo')) {
            //If file is valid
            if (request()->file('photo')->isValid()){
                //Change original name of the file
                $filename = hash_file('haval160,4',request('photo')->getPathname()).'.'.request('photo')->getClientOriginalExtension();
                request('photo')->move(public_path('img/profile-pictures/'), $filename);
                $user->picture = $filename;
            }else{
               // php artisan drop table
            }
        }
        $user->city = request('city');
        $user->description = request('description');
        //Save changes in user profile
        $user->update();

        //return request()->all();
        return redirect('profile');
    }

    public function visit(User $user){

        //Show user's profile 
        return view('profile', compact('user'));
    }
}
