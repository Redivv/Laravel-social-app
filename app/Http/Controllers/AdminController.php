<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;

use App\User;
use App\City;
use Conner\Tagging\Model\Tag;

class AdminController extends Controller
{

    public function __construct() {
        $this->middleware('admin');
    }

    public function index()
    {
        $pictureTicketsAmount = count(Auth::user()->notifications()->where('type', 'App\Notifications\NewProfilePicture')->get());
        $userTicketsAmount = count(Auth::user()->notifications()->where('type', 'App\Notifications\UserFlagged')->get());
        return view('adminPanel')->with('pictureTickets',$pictureTicketsAmount)->with('userTickets',$userTicketsAmount);
    }

    public function getTabContent(Request $request)
    {
        if ($request->ajax()) {
            $target = $request->validate([
                'target'    => [
                    'string',
                    Rule::in(['profileTicket', 'userTicket','userList','tagList','cityList']),
                ]
            ]);

            switch ($target['target']) {
                case 'profileTicket':
                    $validTickets = $this->getProfileTickets();
                    $html = view('partials.admin.profileTicketContent')->withTickets($validTickets)->render();
                    break;
                
                case 'userTicket':
                    $validTickets = $this->getUserTickets();
                    $html = view('partials.admin.userTicketContent')->withTickets($validTickets)->render();
                    break;
                case 'userList':
                    $elements = $this->getUsers();
                    $html = view('partials.admin.userListContent')->withElements($elements)->render();
                    break;
                case 'tagList':
                    $elements = $this->getTags();
                    $html = view('partials.admin.tagListContent')->withElements($elements)->render();
                    break;
                case 'cityList':
                    $elements = $this->getCities();
                    $html = view('partials.admin.cityListContent')->withElements($elements)->render();
                    break;
                    
            }
            return response()->json(['status' => 'success', 'html' => $html], 200);  
        }
          
    }

    public function getProfileTickets() : array
    {
        $tickets = Auth::user()->notifications()->where('type', 'App\Notifications\NewProfilePicture')->get();
        $validTickets = array();
        foreach ($tickets as $ticket) {
            $validUser = User::where('name','=',$ticket->data['user_name'])->where('pending_picture','=',$ticket->data['image'])->first();
            if($validUser){
                $validTickets[] = $ticket;
            }else{
                $ticket->delete();
            }
        }
        return $validTickets;
    }

    public function getUserTickets() : array
    {
        $tickets = Auth::user()->notifications()->where('type', 'App\Notifications\UserFlagged')->get();
        $validTickets = array();
        foreach ($tickets as $ticket) {
            $validUser = User::where('name','=',$ticket->data['user_name'])->first();
            if($validUser){
                $validTickets[] = $ticket;
            }else{
                $ticket->delete();
            }
        }
        return $validTickets;
    }

    public function getUsers() : object
    {
        return User::all();
    }

    public function getTags() : object
    {
        $keke = Tag::first();
        return Tag::all();
    }

    public function getCities() : object
    {
        return City::all();
    }
}
