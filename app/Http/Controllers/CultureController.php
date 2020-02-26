<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\cultureItem;
use App\cultureCategory;
use App\cultureComment;


class CultureController extends Controller
{
    public function index(){
        return view('cultureMainPage');
    }
    public function item(cultureItem $id){

        $categories = cultureCategory::select('id','name','attributes')->where('id','=',$id->category_id)->first();
        
        $similarEntries= cultureItem::select('name','name_slug','pictures','category_id')
            ->where('id','!=',$id->id)
            ->where('category_id','=',$id->category_id)
            ->get(3);
        return view('cultureItem')->withcultureItem($id)->withcultureCategory($categories)->withSimilarEntries($similarEntries);
    }
}
