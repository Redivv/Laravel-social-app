<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;

class SearchController extends Controller
{
    public function index(Request $request) {

        $search_results = null;
        $search_results_variable = null;
        
        if ($request->has(['username','age-min','age-max'])) {
            $search_results = $this->getSearchResults($request);
        }
        elseif (Auth::check()) {
            $search_results_variable = $this->getSimmilarAgeUsers(Auth::user());
        }
        return view('searcher')->withResults($search_results)->withResultsVar($search_results_variable)->withYear(date('Y'));
    }

    public function getSearchResults(object $request_data) : object
    {
        if ($request_data->input('age-min') === null && $request_data->input('age-max') !== null ) {
            $validated_data = $request_data->validate([
                'username' => ['string',  'nullable', 'max:255'],
                'age-max'  => ['integer', 'nullable', 'min:18']
            ]);
        }elseif ($request_data->input('age-min') !== null && $request_data->input('age-max') === null ) {
            $validated_data = $request_data->validate([
                'username' => ['string',  'nullable', 'max:255'],
                'age-min'  => ['integer', 'nullable', 'min:18']
            ]);
        }else{
            $validated_data = $request_data->validate([
                'username' => ['string',  'nullable', 'max:255'],
                'age-min'  => ['integer', 'nullable', 'min:18', 'lte:age-max'],
                'age-max'  => ['integer', 'nullable', 'min:18', 'gte:age-min']
            ]);
        }

        $search_results = User::select('id','name','birth_year','description as desc', 'city', 'picture');

        $validated_data['username'] === null ?: $search_results = $search_results->where('name', 'like', $validated_data['username'].'%');
        
        $current_year = date('Y');
        if (!isset($validated_data['age-min']) && isset($validated_data['age-max'])) {
            $search_results = $search_results->where('birth_year', '>=', $current_year-$validated_data['age-max']);
        }elseif(!isset($validated_data['age-max']) && isset($validated_data['age-min'])){
            $search_results = $search_results->where('birth_year', '<=', $current_year-$validated_data['age-min']);
        }else{
            $validated_data['age-min'] === null && $validated_data['age-max'] === null ?: $search_results = $search_results->whereBetween('birth_year', [$current_year-$validated_data['age-max'],$current_year-$validated_data['age-min']]);
        }

        $request_data->user()   === null ?: $search_results = $search_results->whereNotIn('id',[$request_data->user()->id]);

        $search_results = $search_results->orderBy('birth_year', 'desc')->get();
        return $search_results;
    }

    public function getSimmilarAgeUsers(object $authenticated_user) : object
    {
        $current_year = date('Y');
        $search_results = User::select('id','name','birth_year','description as desc', 'city', 'picture')
            ->whereBetween('birth_year',[$authenticated_user->birth_year-5, $authenticated_user->birth_year+5])
            ->whereNotIn('id',[$authenticated_user->id])
            ->inRandomOrder()
            ->take(10)
            ->get();
        return $search_results;
    }
}
