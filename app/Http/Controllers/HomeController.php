<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Nahid\Talk\Facades\Talk;
use Auth;

use App\Notifications\UserFlagged;
use App\User;
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
        if (Auth::user()->isAdmin()) {
            return redirect(route('adminHome'));
        }
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
}
