<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class SearchController extends Controller
{
    public function index(Request $request) {
        $search_results = null;
        
        if ($request->has(['username','age-min','age-max'])) {
            $search_results = $this->getSearchResults($request);
        }

        return view('searcher')->withResults($search_results);
    }

    public function getSearchResults(object $request_data) : object
    {
        $validated_data = $request_data->validate([
            'username' => ['string',  'nullable', 'max:255'],
            'age-min'  => ['integer', 'nullable', 'min:18', 'lte:age-max'],
            'age-max'  => ['integer', 'nullable', 'min:18', 'gte:age-min']
        ]);

        $search_results = User::select('name');

        $validated_data['username'] === null ?: $search_results = $search_results->where('name', 'like', $validated_data['username'].'%');
        $validated_data['age-min'] === null && $validated_data['age-max'] === null ?: $search_results = $search_results->whereBetween('age', [$validated_data['age-min'],$validated_data['age-max']]);

        $search_results = $search_results->orderBy('age', 'asc')->get();
        return $search_results;
    }
}
