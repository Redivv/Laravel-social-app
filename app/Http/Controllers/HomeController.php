<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Nahid\Talk\Facades\Talk;
use Auth;

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
}
