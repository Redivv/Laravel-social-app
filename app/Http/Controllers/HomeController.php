<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Nahid\Talk\Facades\Talk;
use Auth;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    public function readNotifications(Request $request)
    {
        $request->validate([
            'type'    => [
                'string',
                Rule::in(['userNotifications','systemNotifications']),
            ]
        ]);

        switch ($request->type) {
            case 'userNotifications':
                # code...
                break;
            
            case 'systemNotifications':
                DB::table('notifications')
                    ->whereIn('type',[
                        'App\Notifications\NewProfilePicture',
                        'App\Notifications\UserFlagged',
                        'App\Notifications\AcceptedPicture',
                        'App\Notifications\DeniedPicture'
                    ])
                    ->where('notifiable_id',Auth::id())
                    ->update(['read_at' => Carbon::now()->toDateTimeString()]);
                break;
        }
        return response()->json(['status' => 'success'], 200);
    }

    public function deleteNotifications(Request $request)
    {
        $request->validate([
            'type'    => [
                'string',
                Rule::in(['usNoNot','sysNoNot']),
            ]
        ]);

        switch ($request->type) {
            case 'usNoNot':
                # code...
                break;
            
            case 'sysNoNot':
                DB::table('notifications')
                    ->whereIn('type',[
                        'App\Notifications\AcceptedPicture',
                        'App\Notifications\DeniedPicture'
                    ])
                    ->where('notifiable_id',Auth::id())
                    ->delete();
                break;
        }
        return response()->json(['status' => 'success'], 200);
    }
}
