<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\User;
use App\City;

class SearchController extends Controller
{
    public function index(Request $request) {

        $search_results = null;
        $search_results_variable = null;
        $cities = City::all();

        $offlineTimer = Carbon::now()->subMinutes(30)->toDateTimeString();
        User::where('status','online')->where('updated_at','<',$offlineTimer)->update(['status' => 'offline', 'updated_at' => Carbon::now()->toDateTimeString()]);

        
        if ($request->has(['username','age-min','age-max','city','sortOptions_crit','sortOptions_dir'])) {
            $search_results = $this->getSearchResults($request);
            $header = $search_results[1];
            $search_results = $search_results[0];
        }
        elseif (Auth::check()) {
            switch (rand(1,3)) {
                case 1:
                    $search_results_variable = $this->getSimmilarAgeUsers(Auth::user());
                    break;
                
                case 2:
                    $search_results_variable = $this->getSameRegionUsers(Auth::user());
                    break;
                case 3:
                    $search_results_variable = $this->getSameHobbyUsers(Auth::user());
                    break;
            }

            $header = $search_results_variable[1];
            $search_results_variable = $search_results_variable[0];
        }else{
            $search_results_variable = $this->getRandomUsers();

            $header = $search_results_variable[1];
            $search_results_variable = $search_results_variable[0];
        }
        return view('searcher')->withResults($search_results)->withResultsVar($search_results_variable)->withYear(date('Y'))->withCities($cities)->withHeader($header);
    }

    public function getSearchResults(object $request_data) : array
    {
        if ($request_data->input('age-min') === null && $request_data->input('age-max') !== null ) {
            $validated_data = $request_data->validate([
                'username' => ['string',  'nullable', 'max:255'],
                'age-max'  => ['integer', 'nullable', 'min:18'],
                'city'     => ['string','nullable','exists:cities,name'],
                'sortOptions_crit'      => [
                    'string',
                    Rule::in(['birth_year', 'name','created_at']),
                ],
                'sortOptions_dir'       => [
                    'string',
                    Rule::in(['asc', 'desc']),
                ],
                'hobby.*'               => ['distinct','string','max:255']
            ]);
        }elseif ($request_data->input('age-min') !== null && $request_data->input('age-max') === null ) {
            $validated_data = $request_data->validate([
                'username'              => ['string',  'nullable', 'max:255'],
                'age-min'               => ['integer', 'nullable', 'min:18'],
                'city'                  => ['string','nullable','exists:cities,name'],
                'sortOptions_crit'      => [
                    'string',
                    Rule::in(['birth_year', 'name','created_at']),
                ],
                'sortOptions_dir'       => [
                    'string',
                    Rule::in(['asc', 'desc']),
                ],
                'hobby.*'               => ['distinct','string','max:255']
            ]);
        }else{
            $validated_data = $request_data->validate([
                'username' => ['string',  'nullable', 'max:255'],
                'age-min'  => ['integer', 'nullable', 'min:18', 'lte:age-max'],
                'age-max'  => ['integer', 'nullable', 'min:18', 'gte:age-min'],
                'city'     => ['string','nullable','exists:cities,name'],
                'sortOptions_crit'      => [
                    'string',
                    Rule::in(['birth_year', 'name','created_at']),
                ],
                'sortOptions_dir'       => [
                    'string',
                    Rule::in(['asc', 'desc']),
                ],
                'hobby.*'               => ['distinct','string','max:255'] 
            ]);
        }

        if(!(Auth::check())){ //Guests cannot find users with hidden_status==2;
            $search_results = User::select('users.id','users.name','users.birth_year','users.description as desc', 'users.status', 'users.picture','cities.name as city')
            ->whereNotIn('hidden_status',[2])
            ->leftJoin('cities', 'users.city_id', '=', 'cities.id');
        }else{
            $search_results = User::select('users.id','users.name','users.picture','users.description as desc','users.birth_year', 'users.status', 'cities.name as city')->leftJoin('cities', 'users.city_id', '=', 'cities.id');

        }
        $validated_data['username'] === null ?: $search_results = $search_results->where('users.name', 'like', $validated_data['username'].'%');
        
        $current_year = date('Y');
        if (!isset($validated_data['age-min']) && isset($validated_data['age-max'])) {
            $search_results = $search_results->where('birth_year', '>=', $current_year-$validated_data['age-max']);
        }elseif(!isset($validated_data['age-max']) && isset($validated_data['age-min'])){
            $search_results = $search_results->where('birth_year', '<=', $current_year-$validated_data['age-min']);
        }else{
            $validated_data['age-min'] === null && $validated_data['age-max'] === null ?: $search_results = $search_results->whereBetween('birth_year', [$current_year-$validated_data['age-max'],$current_year-$validated_data['age-min']]);
        }

        if(isset($validated_data['hobby'])){
            $search_results = $search_results->withAnyTag($validated_data['hobby']);
        }

        if ($validated_data['city'] !== null) {
            $selected_city = City::where('name_slug', Str::slug($validated_data['city']))->first();
            $search_results = $search_results->where("city_id","=",$selected_city->id);
        }

        $request_data->user()   === null ?: $search_results = $search_results->whereNotIn('users.id',[$request_data->user()->id]);

        $search_results = $search_results->orderBy('users.'.$validated_data['sortOptions_crit'], $validated_data['sortOptions_dir'])->paginate(5);
        
        return [$search_results,__('searcher.resultNormal',['number' => count($search_results)])];
    }

    public function getSimmilarAgeUsers(object $authenticated_user) : array
    {
        $current_year = date('Y');
        $search_results = User::select('users.id','users.name','users.picture','users.description as desc','users.status','users.birth_year','cities.name as city');
        $search_results = $search_results->whereBetween('birth_year',[$authenticated_user->birth_year-5, $authenticated_user->birth_year+5])
            ->whereNotIn('users.id',[$authenticated_user->id])
            ->leftJoin('cities', 'users.city_id', '=', 'cities.id')
            ->inRandomOrder()
            ->take(5)
            ->get();

        if (count($search_results) <= 0) {
            return $this->getRandomUsers();
        }

        return [$search_results,__('searcher.resultAge')];
    }

    public function getSameRegionUsers(object $authenticated_user) : array
    {
        if (!$authenticated_user->city_id) {
            return $this->getSimmilarAgeUsers($authenticated_user);
        }

        $search_results = User::select('users.id','users.name','users.picture','users.description as desc','users.status','users.birth_year','cities.name as city');
        $search_results = $search_results->where('city_id','=',$authenticated_user->city_id)
            ->whereNotIn('users.id',[$authenticated_user->id])
            ->leftJoin('cities', 'users.city_id', '=', 'cities.id')
            ->inRandomOrder()
            ->take(5)
            ->get();

        if (count($search_results) <= 0) {
            return $this->getSimmilarAgeUsers($authenticated_user);
        }

        return [$search_results,__('searcher.resultCity',['city' => City::find($authenticated_user->city_id)->name])];
    }

    public function getSameHobbyUsers(object $authenticated_user) : array
    {
        $userTags = $authenticated_user->tagNames();
        if (!$userTags) {
            return $this->getSimmilarAgeUsers($authenticated_user);
        }

        $searchedTag = $userTags[array_rand($userTags)];

        $search_results = User::select('users.id','users.name','users.picture','users.description as desc','users.status','users.birth_year','cities.name as city');
        $search_results = $search_results->withAnyTag($userTags)
            ->whereNotIn('users.id',[$authenticated_user->id])
            ->leftJoin('cities', 'users.city_id', '=', 'cities.id')
            ->inRandomOrder()
            ->take(5)
            ->get();

        if (count($search_results) <= 0) {
            return $this->getSimmilarAgeUsers($authenticated_user);
        }
        
        return [$search_results,__('searcher.resultHobby',['hobby' => $searchedTag])];
    }

    public function getRandomUsers() : array    
    {
        $search_results = User::select('users.id','users.name','users.birth_year','users.description as desc', 'users.status', 'users.picture','cities.name as city')
            ->whereNotIn('hidden_status',[2])
            ->leftJoin('cities', 'users.city_id', '=', 'cities.id')
            ->inRandomOrder()
            ->take(5)
            ->get();
        
        return [$search_results,__('searcher.resultRandom')];
    }
}
