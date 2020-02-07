<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\User;
use App\City;
use App\Post;
use Conner\Tagging\Model\Tag;

use Carbon\Carbon;

use Illuminate\Support\Facades\Notification;

use App\Notifications\UserNotification;
use App\Notifications\SystemNotification;

use App\Jobs\SendAdminNewsletter;
use App\Jobs\SendAdminWideInfo;
use App\Jobs\DeleteNonVerifiedUsers;

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
                'decision'     => [
                    'string',
                    Rule::in(['accept','refuse'])
                ]
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
                'decision'     => [
                    'string',
                    Rule::in(['delete','edit'])
                ],
                'target'       =>[
                    'string',
                    Rule::in(['userList','tagList','cityList'])
                ]
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

    public function wideInfo(Request $request)
    {
        if($request->ajax()){
            $request->validate([
                'infoNotCheck'  => ['nullable','string'],
                'infoNotDesc'   => ['nullable','string','max:255','required_with:infoNotCheck'],
                'infoWallCheck' => ['nullable','string'],
                'infoWallDesc'  => ['nullable','string','max:255'],
                'infoMailCheck' => ['nullable','string'],
                'infoMailTitle' => ['nullable','string','max:255'],
                'infoMailDesc'  => ['nullable','string','max:255'],
                'postPicture.*' => ['nullable','file','image','max:5000', 'mimes:jpeg,png,jpg,gif,svg'],
            ]);

            if ( (isset($request->infoNotCheck))){
                if(!empty(trim($request->infoNotDesc))){
                    $users = User::whereNotIn('id',[Auth::id()])->get();
                    SendAdminWideInfo::dispatch($request->infoNotDesc,$users)->delay(now()->addMinutes(2));;
                }else{
                    return response()->json(['status' => 'error', 'message' => 'Empty Message'], 400);
                }
            }

            if (isset($request->infoMailCheck)) {
                $subject = $request->infoMailTitle;
                $desc = $request->infoMailDesc;

                if( (!empty(trim($subject))) && (!empty(trim($desc))) ){

                    $users = User::whereNotIn('id',[Auth::id()])->where('newsletter_status',1)->get();
                    
                    SendAdminNewsletter::dispatch($subject,$desc,$users)->delay(now()->addMinutes(1));

                }else{
                    return response()->json(['status' => 'error', 'message' => 'Empty Message'], 400);
                }
            }
            return response()->json(['status' => 'success'], 200);
        }
    }

    public function getPagi(Request $request)
    {
        if ($request->ajax()) {

            $request->validate([
                'pagiTarget'    => [
                    'string',
                    Rule::in(['profileTicket', 'userTicket','userList','tagList','cityList']),
                ],
                'pagiCount' => ['numeric','min:0']
            ]);

            $pagiTarget = $request->pagiTarget;
            $pagiCount = $request->pagiCount;
            $pagiNext = true;

            switch ($pagiTarget) {
                case 'userList':
                    $users = User::whereNotIn('id',[Auth::id()])->whereNotNull('email_verified_at')->skip(5*$pagiCount)->take(5)->get();
                    if (count($users) < 5) {
                        $pagiNext = false;
                    }
                    $html = view('partials.admin.userListPagi')->withUsers($users)->render();
                    break;
                case 'tagList':
                    $tags = Tag::skip(10*$pagiCount)->take(10)->get();
                    if (count($tags) < 10) {
                        $pagiNext = false;
                    }
                    $html = view('partials.admin.tagListPagi')->withTags($tags)->render();
                    break;
                case 'cityList':
                    $cities = City::take(10)->skip(10*$pagiCount)->get();
                    if (count($cities) < 10) {
                        $pagiNext = false;
                    }
                    $html = view('partials.admin.cityListPagi')->withCities($cities)->render();
                    break;
                case 'profileTicket':
                    $tickets = Auth::user()->notifications()->where('type', 'App\Notifications\NewProfilePicture')->take(5)->skip(5*$pagiCount)->get();
                    $validTickets = array();
                    foreach ($tickets as $ticket) {
                        $validUser = User::where('name','=',$ticket->data['user_name'])->where('pending_picture','=',$ticket->data['image'])->first();
                        if($validUser){
                            $validTickets[] = $ticket;
                        }else{
                            $ticket->delete();
                        }
                    }
                    if (count($validTickets) < 5) {
                        $pagiNext = false;
                    }
                    $html = view('partials.admin.profileTicketPagi')->withTickets($validTickets)->render();
                    break;
                case 'userTicket':
                    $tickets = Auth::user()->notifications()->where('type', 'App\Notifications\UserFlagged')->take(10)->skip(10*$pagiCount)->get();
                    $validTickets = array();
                    $duplicateAuthors = array();
                    foreach ($tickets as $ticket) {
                        if (!in_array($ticket->data['author'],$duplicateAuthors)) {
                            $duplicateAuthors[] = $ticket->data['author'];
                            $validUser = User::where('name','=',$ticket->data['user_name'])->first();
                            if($validUser){
                                $validTickets[] = $ticket;
                            }else{
                                $ticket->delete();
                            }
                        }else{
                            $ticket->delete();
                        }
                    }
                    if (count($validTickets) < 10) {
                        $pagiNext = false;
                    }
                    $html = view('partials.admin.userTicketPagi')->withTickets($validTickets)->render();

                    break;
            }

            return response()->json(['status' => 'success','html' => $html, 'pagiNext' => $pagiNext], 200);
        }
    }

    public function searchList(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'target'    => [
                    'string',
                    Rule::in(['userList','tagList','cityList']),
                ],
                'criteria'  => [
                    'string'
                ]
            ]);
            
            $target = $request->target;
            $criteria = $request->criteria;

            switch ($target) {
                case 'userList':
                    $users = User::where('name','like','%'.$criteria.'%')->get();
                    if (count($users) <= 0) {
                        $html = "<span class='searchNoResults'>".__('admin.noSearchResults')."</span>";
                    }else{
                        $html = view('partials.admin.userListTable')->withElements($users)->render();
                    }
                    break;
                
                case 'tagList':
                    $tags = Tag::where('name','like','%'.$criteria.'%')->orWhere('slug','like','%'.$criteria.'%')->get();
                    if (count($tags) <= 0) {
                        $html = "<span class='searchNoResults'>".__('admin.noSearchResults')."</span>";
                    }else{
                        $html = view('partials.admin.tagListTable')->withElements($tags)->render();
                    }
                    break;
                case 'cityList':
                    $cities = City::where('name','like','%'.$criteria.'%')->orWhere('name_slug','like','%'.$criteria.'%')->get();
                    if (count($cities) <= 0) {
                        $html = "<span class='searchNoResults'>".__('admin.noSearchResults')."</span>";
                    }else{
                        $html = view('partials.admin.cityListTable')->withElements($cities)->render();
                    }
                    break;
            }

            return response()->json(['status' => 'success','html' => $html], 200);

        }
    }

    private function getProfileTickets() : array
    {
        $tickets = Auth::user()->notifications()->where('type', 'App\Notifications\NewProfilePicture')->take(5)->get();
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
        $tickets = Auth::user()->notifications()->where('type', 'App\Notifications\UserFlagged')->take(10)->get();
        $validTickets = array();
        $duplicateAuthors = array();
        foreach ($tickets as $ticket) {
            if (!in_array($ticket->data['author'],$duplicateAuthors)) {
                $duplicateAuthors[] = $ticket->data['author'];
                $validUser = User::where('name','=',$ticket->data['user_name'])->first();
                if($validUser){
                    $validTickets[] = $ticket;
                }else{
                    $ticket->delete();
                }
            }else{
                $ticket->delete();
            }
        }
        return $validTickets;
    }

    private function getUsers() : object
    {
        DeleteNonVerifiedUsers::dispatchNow();

        return User::whereNotIn('id',[Auth::id()])->whereNotNull('email_verified_at')->take(5)->get();
    }

    private function getTags() : object
    {
        return Tag::take(10)->get();
    }

    private function getCities() : object
    {
        return City::take(10)->get();
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
                    $validUser->notify(new SystemNotification(__('nav.pictureOk'),'success','_user_profile','','','userPictureOk'));

                    $post = new Post;
                    $post->user_id      = $validUser->id;
                    $post->is_public    = false;
                    $post->pictures     = json_encode([$validUser->picture]);
                    $post->type         = "newPicture";
                    $post->tagged_users = json_encode([$validUser->name]);

                    copy(public_path('img/profile-pictures/').$validUser->picture,public_path('img/post-pictures/').$validUser->picture);


                    if ($post->save()) {
                        Notification::send($validUser->getFriends(), new UserNotification($validUser, '_user_home_post_',$post->id, '', __('nav.userNot3'), 'newPost'.$post->id));
                    }

                    break;
                case 'refuse':
                    $validUser->pending_picture = null;
                    $validUser->update();
                    unlink(public_path('img/profile-pictures/'.$data['image']));
                    $validUser->notify(new SystemNotification(__('nav.pictureDeny'),'danger','_user_profile','','','userPictureNo'));
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
                    $validUser->deleteAll();
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
                $user->deleteAll();
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
