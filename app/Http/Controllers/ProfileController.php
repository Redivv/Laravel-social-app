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

        $users = User::all();

        return view('profile', compact('users'));




    }



    public function edit(){

        return view('profileEdit');
    }

    public function update(){

        return request()->all();
    }
}
