<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\User;
use App\City;
use Conner\Tagging\Model\Tag;

use App\Notifications\AcceptedPicture;
use App\Notifications\UserDeleted;
use App\Notifications\DeniedPicture;

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
                    $amount = count($validTickets);
                    $html = view('partials.admin.profileTicketContent')->withTickets($validTickets)->render();
                    break;
                case 'userTicket':
                    $validTickets = $this->getUserTickets();
                    $amount = count($validTickets);
                    $html = view('partials.admin.userTicketContent')->withTickets($validTickets)->render();
                    break;
                case 'userList':
                    $elements = $this->getUsers();
                    $amount = null;
                    $html = view('partials.admin.userListContent')->withElements($elements)->render();
                    break;
                case 'tagList':
                    $elements = $this->getTags();
                    $amount = null;
                    $html = view('partials.admin.tagListContent')->withElements($elements)->render();
                    break;
                case 'cityList':
                    $elements = $this->getCities();
                    $amount = null;
                    $html = view('partials.admin.cityListContent')->withElements($elements)->render();
                    break;
                    
            }
            return response()->json(['status' => 'success', 'html' => $html, 'amount' => $amount], 200);  
        }else{
            return response()->json(['status' => 'error'], 400);  
        }
          
    }

    public function resolveTicket(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'decision'     =>
                    'string',
                    Rule::in(['accept','refuse']),
            ]);
            $ticketId = substr($request->ticketId,9);
            $ticket = Auth::user()->notifications()->where('id', $ticketId)->first();
            if ($ticket) {
                switch ($ticket->type) {
                    case 'App\Notifications\NewProfilePicture':
                        $this->resolveProfileTicket($ticket->data, $request->decision);
                        break;
                    case 'App\Notifications\UserFlagged':
                        $this->resolveUserTicket($ticket->data, $request->decision);
                        break;

                    default:
                        return response()->json(['status' => 'error'], 400);
                        break;
                }
                $ticket->delete();
            }
            return response()->json(['status' => 'success'], 200);
        }
    }

    public function resolveListRequest(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'decision'     =>
                    'string', 'required',
                    Rule::in(['delete','edit']),
                'target'       =>
                    'string', 'required',
                    Rule::in(['userList','tagList','cityList']),
            ]);
            $elementId = intVal(substr($request->elementId,10));
            switch ($request->target) {
                case 'userList':
                    $selectedElement = User::find($elementId);
                    if ($selectedElement) {
                        $this->resolveUserList($selectedElement,$request->decision);
                    }else{
                        return response()->json(['status' => 'error'], 400);
                    }
                    break;
                case 'tagList':
                    $selectedElement = Tag::find($elementId);
                    if ($selectedElement) {
                        $this->resolveTagList($selectedElement,$request->decision,$request->editValue);
                    }else{
                        return response()->json(['status' => 'error'], 400);
                    }
                    break;
                case 'cityList':
                    $selectedElement = City::find($elementId);
                    if ($selectedElement) {
                        $this->resolveCityList($selectedElement,$request->decision,$request->editValue);
                    }else{
                        return response()->json(['status' => 'error'], 400);
                    }
                    break;
                default:
                    return response()->json(['status' => 'error'], 400);
                    break;
            }
            return response()->json(['status' => 'success'], 200);
        }
        return response()->json(['status' => 'error'], 400);
    }

    private function getProfileTickets() : array
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

    private function getUserTickets() : array
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

    private function getUsers() : object
    {
        return User::whereNotIn('id',[Auth::id()])->get();
    }

    private function getTags() : object
    {
        $keke = Tag::first();
        return Tag::all();
    }

    private function getCities() : object
    {
        return City::all();
    }

    private function resolveProfileTicket(array $data, string $decision) : void
    {
        $validUser = User::where('name','=',$data['user_name'])->where('pending_picture','=',$data['image'])->first();
        if($validUser){
            switch ($decision) {
                case 'accept':
                    $validUser->picture = $validUser->pending_picture;
                    $validUser->pending_picture = null;
                    $validUser->update();
                    $validUser->notify(new AcceptedPicture($validUser->name,$validUser->picture));
                    break;
                case 'refuse':
                    $validUser->pending_picture = null;
                    $validUser->update();
                    $otherUser = User::where('pending_picture','=',$data['image'])->orWhere('picture','=',$data['image'])->first();
                    if (!$otherUser) {
                        unlink(public_path('img/profile-pictures/'.$data['image']));
                    }
                    $validUser->notify(new DeniedPicture($validUser->name));
                    break;
            }
        }
    }

    private function resolveUserTicket(array $data, string $decision) : void
    {
        $validUser = User::where('name','=',$data['user_name'])->first();
        if($validUser){
            switch ($decision) {
                case 'accept':
                    $convoId = DB::table('conversations')->select('id')->where('user_one',$validUser->id)->orWhere('user_two',$validUser->id)->get()->toArray();
                    foreach ($convoId as $convo) {
                        DB::table('conversations')->where('id',$convo->id)->delete();
                        DB::table('messages')->where('conversation_id',$convo->id)->delete();
                    }
                    DB::table('posts')->where('user_id',$validUser->id)->delete();
                    $validUser->notify(new UserDeleted($validUser->name));
                    $validUser->delete();
                    break;
                case 'refuse':
                    break;
            }
        }
    }

    private function resolveUserList(object $user,string $decision) : void
    {
        switch ($decision) {
            case 'delete':
                $convoId = DB::table('conversations')->select('id')->where('user_one',$user->id)->orWhere('user_two',$user->id)->get()->toArray();
                foreach ($convoId as $convo) {
                    DB::table('conversations')->where('id',$convo->id)->delete();
                    DB::table('messages')->where('conversation_id',$convo->id)->delete();
                }
                DB::table('posts')->where('user_id',$validUser->id)->delete();
                $user->notify(new UserDeleted($user->name));
                $user->delete();
                break;
        }
    }

    private function resolveTagList(object $tag, string $decision, ?string $edit)
    {
        switch ($decision) {
            case 'delete':
                DB::table('tagging_tagged')->where('tag_name','=',$tag->name)->where('tag_slug','=',$tag->slug)->delete();
                $tag->delete();
                break;
            
            case 'edit':
                DB::table('tagging_tagged')->where('tag_name','=',$tag->name)->where('tag_slug','=',$tag->slug)->update(['tag_name' => Str::title($edit), 'tag_slug' => Str::slug($edit)]);
                $tag->name = Str::title($edit);
                $tag->slug = Str::slug($edit);
                $tag->update();
                break;
        }
    }

    private function resolveCityList(object $city, string $decision, ?string $edit)
    {
        switch ($decision) {
            case 'delete':
                User::where('city_id','=',$city->id)->update(['city_id' => null]);
                $city->delete();
                break;
            
            case 'edit':
                $city->name = Str::title($edit);
                $city->name_slug = Str::slug($edit);
                $city->update();
                break;
        }
    }
}
