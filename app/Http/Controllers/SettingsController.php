<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('settings')->withUser($user);
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'status'            => [
                'required',
                'numeric',
                Rule::in([0,1,2]),
            ],
            'newsletter'        => ['required','boolean'] 
        ]);

        $user = Auth::user();

        $user->hidden_status        = $request->status;
        $user->newsletter_status    = $request->newsletter;

        if($user->update()){
            return redirect(route('SettingsPage'));
        }
    }
}
