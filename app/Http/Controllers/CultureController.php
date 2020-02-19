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

    public function newCategory(Request $request)
    {
        $kek = $request->all();
        return response()->json(['status' => 'success'], 200);
    }
}
