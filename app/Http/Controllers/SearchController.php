<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return view('searcher')->withResults($search_results)->withResultsVar($search_results_variable);
    }

    public function getSearchResults(object $request_data) : object
    {
        $validated_data = $request_data->validate([
            'username' => ['string',  'nullable', 'max:255'],
            'age-min'  => ['integer', 'nullable', 'min:18', 'lte:age-max'],
            'age-max'  => ['integer', 'nullable', 'min:18', 'gte:age-min']
        ]);

        $search_results = User::select('id','name','age','description as desc', 'city', 'picture');

        $validated_data['username'] === null ?: $search_results = $search_results->where('name', 'like', $validated_data['username'].'%');
        $validated_data['age-min'] === null && $validated_data['age-max'] === null ?: $search_results = $search_results->whereBetween('age', [$validated_data['age-min'],$validated_data['age-max']]);
        $request_data->user()   === null ?: $search_results = $search_results->whereNotIn('id',[$request_data->user()->id]);

        $search_results = $search_results->orderBy('age', 'asc')->get();
        return $search_results;
    }

    public function getSimmilarAgeUsers(object $authenticated_user) : object
    {
        $search_results = User::select('id','name','age','description as desc', 'city', 'picture')
            ->whereBetween('age',[$authenticated_user->age-5,$authenticated_user->age+3])
            ->whereNotIn('id',[$authenticated_user->id])
            ->inRandomOrder()
            ->take(10)
            ->get();
        return $search_results;
    }
}
