<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index() {
        $data = request()->validate([
            'name' => ['string', 'max:255'],
            'age-min' => ['integer', 'min:18', 'lt:age-max'],
            'age-max' => ['integer', 'min:18', 'gt:age-min']
        ]);

        return view('searcher')->withData($data);
    }
}
