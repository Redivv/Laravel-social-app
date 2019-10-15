<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AjaxTagsController extends Controller
{
    public function addNew(Request $request,$name)
    {
        return response()->json(['status' => 'success'],200);
    }

    public function deleteTag($id)
    {
        return response()->json(['status' => 'success'], 200);
    }
}
