<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index( ?int $age_min = null, ?int $age_max = null, ?string $username = null) {
        return view('searcher');
    }
}
