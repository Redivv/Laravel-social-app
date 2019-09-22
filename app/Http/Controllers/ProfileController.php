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

        return request()->all();
    }
}
