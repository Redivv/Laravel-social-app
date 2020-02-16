<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CultureController extends Controller
{
    public function index()
    {
        return view('cultureMainPage');
    }

    public function searchResults(Request $request)
    {
        return view('cultureSearchResults');
    }
}
