<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function __construct() {
        $this->middleware('admin');
    }

    public function index()
    {
        return view('adminPanel');
    }

    public function getTabContent(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(['status' => 'success'], 200);  
        }
          
    }
}
