<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\User;
use App\City;
use App\cultureCategory;
use App\cultureItem;
use Conner\Tagging\Model\Tag;

use Carbon\Carbon;

use App\Jobs\SendAdminNewsletter;
use App\Jobs\SendAdminWideInfo;
use App\Jobs\DeleteUser;
use App\Jobs\HandleProfilePictureTicket;
use App\Partner;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Nahid\Talk\Facades\Talk;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $pictureTicketsAmount = count(Auth::user()->notifications()->where('type', 'App\Notifications\NewProfilePicture')->get());
        $userTicketsAmount = count(Auth::user()->notifications()->where('type', 'App\Notifications\UserFlagged')->get());

        $inactiveTimer = Carbon::now()->subDays(4)->toDateTimeString();
        $inactiveUsers = User::where('created_at', '<', $inactiveTimer)
            ->whereNull('pending_picture')
            ->whereNotIn('id', [1])
            ->where(function ($query) {
                $query->whereNull('email_verified_at')->orWhere('picture', 'default-picture.png');
            })->take(10)->count();

        $userTicketsAmount += $inactiveUsers;
        return view('adminPanel')->with('pictureTickets', $pictureTicketsAmount)->with('userTickets', $userTicketsAmount);
    }

    public function culture(Request $request)
    {
        $itemCategories = cultureCategory::all();
        $partners   = Partner::all();

        $request->validate([
            'elementType'   => Rule::in(['cultureCategory', 'cultureItem']),
        ]);

        $editingElement = null;
        $editingType = null;

        if ((isset($request->elementType))) {
            switch ($request->elementType) {
                case 'cultureCategory':
                    $request->validate([
                        'elementId'   => ['numeric','exists:culture_categories,id'],
                    ]);
                    $editingElement = cultureCategory::find($request->elementId);
                    $editingType    = "category";
                    break;
                case 'cultureItem':
                    $request->validate([
                        'elementId'   => ['numeric','exists:culture_items,id'],
                    ]);
                    $editingElement = cultureItem::find($request->elementId);
                    $editingType    = "item";
                    break;
            }
        }
        return view('adminCulturePanel')->withElement($editingElement)->withElementType($editingType)->withCategories($itemCategories)->withPartners($partners);
    }

    public function getTabContent(Request $request)
    {
        if ($request->ajax()) {
            $validTargets = ['profileTicket', 'userTicket', 'userList', 'tagList', 'cityList', 'cultureItems', 'cultureCategories'];

            $target = $request->validate([
                'target'    => [
                    'string',
                    Rule::in($validTargets),
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
                    $inactiveUsers = $this->getInactiveUsers();
                    $amount = count($validTickets);
                    $amount = $amount + count($inactiveUsers);
                    $html = view('partials.admin.userTicketContent')->withTickets($validTickets)->withUsers($inactiveUsers)->render();
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
                case 'cultureCategories':
                    $elements = $this->getCultureCategories();
                    $amount = null;
                    $html = view('partials.admin.culture.cultureCategoriesContent')->withElements($elements)->render();
                    break;
            }
            return response()->json(['status' => 'success', 'html' => $html, 'amount' => $amount], 200);
        } else {
            return response()->json(['status' => 'error'], 400);
        }
    }

    public function resolveTicket(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'decision'     => [
                    'string',
                    Rule::in(['accept', 'refuse'])
                ]
            ]);
            $ticketId = substr($request->ticketId, 9);
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
                    Rule::in(['delete', 'edit', 'writeEmail', 'writeProfile'])
                ],
                'target'       => [
                    'string',
                    Rule::in(['userTicket', 'userList', 'tagList', 'cityList', 'cultureCategories'])
                ]
            ]);
            $elementId = intVal(substr($request->elementId, 10));
            switch ($request->target) {
                case 'userList':
                    $selectedElement = User::find($elementId);
                    if ($selectedElement) {
                        $this->resolveUserList($selectedElement, $request->decision);
                    } else {
                        return response()->json(['status' => 'error'], 400);
                    }
                    break;
                case 'tagList':
                    $selectedElement = Tag::find($elementId);
                    if ($selectedElement) {
                        $this->resolveTagList($selectedElement, $request->decision, $request->editValue);
                    } else {
                        return response()->json(['status' => 'error'], 400);
                    }
                    break;
                case 'cityList':
                    $selectedElement = City::find($elementId);
                    if ($selectedElement) {
                        $this->resolveCityList($selectedElement, $request->decision, $request->editValue);
                    } else {
                        return response()->json(['status' => 'error'], 400);
                    }
                    break;
                case 'userTicket':
                    $selectedElement = User::find($elementId);
                    if ($selectedElement) {
                        $this->resolveUserList($selectedElement, $request->decision);
                    } else {
                        return response()->json(['status' => 'error'], 400);
                    }
                    break;
                case 'cultureCategories':
                    $selectedElement = cultureCategory::find($elementId);
                    if ($selectedElement) {
                        $this->resolveCultureCategory($selectedElement);
                    } else {
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
        if ($request->ajax()) {
            $request->validate([
                'infoNotCheck'  => ['nullable', 'string'],
                'infoNotDesc'   => ['nullable', 'string', 'max:255', 'required_with:infoNotCheck'],
                'infoWallCheck' => ['nullable', 'string'],
                'infoWallDesc'  => ['nullable', 'string', 'max:255'],
                'infoMailCheck' => ['nullable', 'string'],
                'infoMailTitle' => ['nullable', 'string', 'max:255'],
                'infoMailDesc'  => ['nullable', 'string', 'max:255'],
                'postPicture.*' => ['nullable', 'file', 'image', 'max:10000', 'mimes:jpeg,png,jpg,gif,svg'],
            ]);

            if ((isset($request->infoNotCheck))) {
                if (!empty(trim($request->infoNotDesc))) {
                    $users = User::whereNotIn('id', [Auth::id()])->get();
                    SendAdminWideInfo::dispatch($request->infoNotDesc, $users)->delay(now()->addMinutes(2));
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Empty Message'], 400);
                }
            }

            if (isset($request->infoMailCheck)) {
                $subject = $request->infoMailTitle;
                $desc = $request->infoMailDesc;

                if ((!empty(trim($subject))) && (!empty(trim($desc)))) {

                    $users = User::whereNotIn('id', [Auth::id()])->where('newsletter_status', 1)->get();

                    SendAdminNewsletter::dispatch($subject, $desc, $users)->delay(now()->addMinutes(1));
                } else {
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
                    Rule::in(['profileTicket', 'userTicket', 'userList', 'tagList', 'cityList']),
                ],
                'pagiCount' => ['numeric', 'min:0']
            ]);

            $pagiTarget = $request->pagiTarget;
            $pagiCount = $request->pagiCount;
            $pagiNext = true;

            switch ($pagiTarget) {
                case 'userList':
                    $users = User::whereNotIn('id', [Auth::id()])->whereNotNull('email_verified_at')->skip(5 * $pagiCount)->take(5)->get();
                    if (count($users) < 5) {
                        $pagiNext = false;
                    }
                    $html = view('partials.admin.userListPagi')->withUsers($users)->render();
                    break;
                case 'tagList':
                    $tags = Tag::skip(10 * $pagiCount)->take(10)->get();
                    if (count($tags) < 10) {
                        $pagiNext = false;
                    }
                    $html = view('partials.admin.tagListPagi')->withTags($tags)->render();
                    break;
                case 'cityList':
                    $cities = City::take(10)->skip(10 * $pagiCount)->get();
                    if (count($cities) < 10) {
                        $pagiNext = false;
                    }
                    $html = view('partials.admin.cityListPagi')->withCities($cities)->render();
                    break;
                case 'profileTicket':
                    $tickets = Auth::user()->notifications()->where('type', 'App\Notifications\NewProfilePicture')->take(5)->skip(5 * $pagiCount)->get();
                    $validTickets = array();
                    foreach ($tickets as $ticket) {
                        $validUser = User::where('name', '=', $ticket->data['user_name'])->where('pending_picture', '=', $ticket->data['image'])->first();
                        if ($validUser) {
                            $validTickets[] = $ticket;
                        } else {
                            $ticket->delete();
                        }
                    }
                    if (count($validTickets) < 5) {
                        $pagiNext = false;
                    }
                    $html = view('partials.admin.profileTicketPagi')->withTickets($validTickets)->render();
                    break;
                case 'userTicket':
                    $tickets = Auth::user()->notifications()->where('type', 'App\Notifications\UserFlagged')->take(10)->skip(10 * $pagiCount)->get();
                    $validTickets = array();
                    $duplicateAuthors = array();
                    foreach ($tickets as $ticket) {
                        if (!in_array($ticket->data['author'], $duplicateAuthors)) {
                            $duplicateAuthors[] = $ticket->data['author'];
                            $validUser = User::where('name', '=', $ticket->data['user_name'])->first();
                            if ($validUser) {
                                $validTickets[] = $ticket;
                            } else {
                                $ticket->delete();
                            }
                        } else {
                            $ticket->delete();
                        }
                    }

                    $inactiveTimer = Carbon::now()->subDays(4)->toDateTimeString();
                    $inactiveUsers = User::where('created_at', '<', $inactiveTimer)
                        ->whereNull('pending_picture')
                        ->whereNotIn('id', [1])
                        ->where(function ($query) {
                            $query->whereNull('email_verified_at')->orWhere('picture', 'default-picture.png');
                        })->take(10)->skip(10 * $pagiCount)->get()->toArray();

                    $admins = User::where('is_admin', 1)->get();

                    foreach ($inactiveUsers as $key => $user) {
                        $carbon = new Carbon($user['created_at']);
                        $inactiveUsers[$key]['created_at'] = $carbon->diffForHumans();
                        foreach ($admins as $admin) {
                            if (Talk::user($admin->id)->isConversationExists($user['id'])) {
                                $inactiveUsers[$key]['adminConvo'] = true;
                                break;
                            } else {
                                $inactiveUsers[$key]['adminConvo'] = false;
                            }
                        }
                    }

                    if ((count($validTickets) < 10) && (count($inactiveUsers) < 10)) {
                        $pagiNext = false;
                    }
                    $html = view('partials.admin.userTicketPagi')->withTickets($validTickets)->withUsers($inactiveUsers)->render();

                    break;
            }

            return response()->json(['status' => 'success', 'html' => $html, 'pagiNext' => $pagiNext], 200);
        }
    }

    public function searchList(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'target'    => [
                    'string',
                    Rule::in(['userList', 'tagList', 'cityList']),
                ],
                'criteria'  => [
                    'string'
                ]
            ]);

            $target = $request->target;
            $criteria = $request->criteria;

            switch ($target) {
                case 'userList':
                    $users = User::where('name', 'like', '%' . $criteria . '%')->get();
                    if (count($users) <= 0) {
                        $html = "<span class='searchNoResults'>" . __('admin.noSearchResults') . "</span>";
                    } else {
                        $html = view('partials.admin.userListTable')->withElements($users)->render();
                    }
                    break;

                case 'tagList':
                    $tags = Tag::where('name', 'like', '%' . $criteria . '%')->orWhere('slug', 'like', '%' . $criteria . '%')->get();
                    if (count($tags) <= 0) {
                        $html = "<span class='searchNoResults'>" . __('admin.noSearchResults') . "</span>";
                    } else {
                        $html = view('partials.admin.tagListTable')->withElements($tags)->render();
                    }
                    break;
                case 'cityList':
                    $cities = City::where('name', 'like', '%' . $criteria . '%')->orWhere('name_slug', 'like', '%' . $criteria . '%')->get();
                    if (count($cities) <= 0) {
                        $html = "<span class='searchNoResults'>" . __('admin.noSearchResults') . "</span>";
                    } else {
                        $html = view('partials.admin.cityListTable')->withElements($cities)->render();
                    }
                    break;
            }

            return response()->json(['status' => 'success', 'html' => $html], 200);
        }
    }

    public function newPartners(Request $request)
    {
        if ($request->ajax()) {
            $kek = $request->all();
            $validatedData  = $this->validatePartnerData($request);

            if ($validatedData) {
                $this->managePartners($validatedData);
            }

            return response()->json(['action' => 'savedData'], 200);

        }
    }



    // Private Functions 

    private function validatePartnerData(Request $data) : Array
    {
       $validatedData = $data->validate([
            'partnersNames.*'           => ['required','string'],
            'partnersUrls.*'            => ['required','string','url'],
            'partnersImages.*'          => ['required','file', 'image', 'max:10000', 'mimes:jpeg,png,jpg,gif,svg'],
            'existingPartners.*.id'     => ['exists:partners,id'],
            'existingPartners.*.name'   => ['string'],
            'existingPartners.*.url'    => ['string','url'],
            'existingPartners.*.image'  => ['file', 'image', 'max:10000', 'mimes:jpeg,png,jpg,gif,svg'],
        ]);
        return $validatedData;
    }

    private function areThreeArraysEqualInSize(Array $array1, Array $array2, Array $array3) : bool
    {
        if ( (sizeof($array1) == sizeof($array2)) && (sizeof($array3) == sizeof($array1)) ) {
            return true;
        }else{
            return false;
        }
    }

    private function managePartners(Array $data) : void
    {
        if (isset($data['existingPartners'])) {
            DB::table('partners')->whereNotIn('id',$data['existingPartners'])->delete();
            foreach ($data['existingPartners'] as $key => $partner) {
                $editingPartner = Partner::find($partner['id']);

                $editingPartner->name = $partner['name'];
                $editingPartner->url  = $partner['url'];

                if (isset($partner['image'])) {
                    $editingPartner->thumbnail = $this->savePartnerThumbnail($partner['image']);
                }

                $editingPartner->update();


            }
        }else{
            DB::table('partners')->delete();
        }
        
        if (isset($data['partnersNames']) && isset($data['partnersUrls']) && isset($data['partnersImages'])) {
            if ($this->areThreeArraysEqualInSize($data['partnersNames'],$data['partnersUrls'],$data['partnersImages'])) {
                foreach ($data['partnersNames'] as $key => $name) {
                    $newPartner = new Partner;
        
                    $newPartner->name = $name;
        
                    $newPartner->url  = $data['partnersUrls'][$key];
        
                    $newPartner->thumbnail = $this->savePartnerThumbnail($data['partnersImages'][$key]);
        
                    $newPartner->save();
                }
            }
        }
    }

    private function savePartnerThumbnail(UploadedFile $picture) : string
    {
        $imageName = hash_file('haval160,4',$picture->getPathname()).'.'.$picture->getClientOriginalExtension();
        $picture->move(public_path('img/partner-pictures'), $imageName);
        return $imageName;
    }


    private function getProfileTickets(): array
    {
        $tickets = Auth::user()->notifications()->where('type', 'App\Notifications\NewProfilePicture')->take(5)->get();
        $validTickets = array();
        foreach ($tickets as $ticket) {
            $validUser = User::where('name', '=', $ticket->data['user_name'])->where('pending_picture', '=', $ticket->data['image'])->first();
            if ($validUser) {
                $validTickets[] = $ticket;
            } else {
                $ticket->delete();
            }
        }
        return $validTickets;
    }

    private function getUserTickets(): array
    {
        $tickets = Auth::user()->notifications()->where('type', 'App\Notifications\UserFlagged')->take(10)->get();
        $validTickets = array();

        $duplicateAuthors = array();
        foreach ($tickets as $ticket) {
            if (!in_array($ticket->data['author'], $duplicateAuthors)) {
                $duplicateAuthors[] = $ticket->data['author'];
                $validUser = User::where('name', '=', $ticket->data['user_name'])->first();
                if ($validUser) {
                    $validTickets[] = $ticket;
                } else {
                    $ticket->delete();
                }
            } else {
                $ticket->delete();
            }
        }

        return $validTickets;
    }

    private function getInactiveUsers(): array
    {
        $inactiveTimer = Carbon::now()->subDays(4)->toDateTimeString();
        $inactiveUsers = User::where('created_at', '<', $inactiveTimer)
            ->whereNull('pending_picture')
            ->whereNotIn('id', [1])
            ->where(function ($query) {
                $query->whereNull('email_verified_at')->orWhere('picture', 'default-picture.png');
            })->take(10)->get()->toArray();

        $admins = User::where('is_admin', 1)->get();

        foreach ($inactiveUsers as $key => $user) {
            $carbon = new Carbon($user['created_at']);
            $inactiveUsers[$key]['created_at'] = $carbon->diffForHumans();
            foreach ($admins as $admin) {
                if (Talk::user($admin->id)->isConversationExists($user['id'])) {
                    $inactiveUsers[$key]['adminConvo'] = true;
                    break;
                } else {
                    $inactiveUsers[$key]['adminConvo'] = false;
                }
            }
        }

        return $inactiveUsers;
    }

    private function getUsers(): object
    {
        return User::whereNotIn('id', [Auth::id()])->whereNotNull('email_verified_at')->take(5)->get();
    }

    private function getTags(): object
    {
        return Tag::take(10)->get();
    }

    private function getCities(): object
    {
        return City::take(10)->get();
    }

    private function getCultureCategories(): Collection
    {
        return cultureCategory::all();
    }

    private function resolveProfileTicket(array $data, string $decision): void
    {
        $validUser = User::where('name', $data['user_name'])->where('pending_picture', $data['image'])->first();
        if ($validUser) {
            HandleProfilePictureTicket::dispatch($validUser, $decision, $data['image']);
        }
    }

    private function resolveUserTicket(array $data, string $decision): void
    {
        $validUser = User::where('name', '=', $data['user_name'])->first();
        if ($validUser) {
            switch ($decision) {
                case 'accept':
                    DeleteUser::dispatch($validUser->id);
                    break;
                case 'refuse':
                    break;
            }
        }
    }

    private function resolveUserList(object $user, string $decision): void
    {
        switch ($decision) {
            case 'delete':
                DeleteUser::dispatch($user->id);
                break;
            case 'writeEmail':
                $body = __('admin.writeEmail');
                $userId = $user->id;
                Talk::user(Auth::id())->sendMessageByUserId($userId, $body);
                break;
            case 'writeProfile':
                $body = __('admin.writeProfile');
                $userId = $user->id;
                Talk::user(Auth::id())->sendMessageByUserId($userId, $body);
                break;
        }
    }

    private function resolveTagList(object $tag, string $decision, ?string $edit)
    {
        switch ($decision) {
            case 'delete':
                DB::table('tagging_tagged')->where('tag_name', '=', $tag->name)->where('tag_slug', '=', $tag->slug)->delete();
                $tag->delete();
                break;

            case 'edit':
                DB::table('tagging_tagged')->where('tag_name', '=', $tag->name)->where('tag_slug', '=', $tag->slug)->update(['tag_name' => Str::title($edit), 'tag_slug' => Str::slug($edit)]);
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
                User::where('city_id', '=', $city->id)->update(['city_id' => null]);
                $city->delete();
                break;

            case 'edit':
                $city->name = Str::title($edit);
                $city->name_slug = Str::slug($edit);
                $city->update();
                break;
        }
    }

    private function resolveCultureCategory(cultureCategory $category)
    {
        $category->delete();
    }
}
