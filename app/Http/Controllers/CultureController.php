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
use App\cultureCimment;


class CultureController extends Controller
{
    public function index(){
        return view('cultureMainPage');
    }
    public function item(cultureItem $id){
        //return($id);
        $categories = cultureCategory::select('name','attributes')->where($id->category_id);
        return view('cultureItem')->withcultureItem($id);
    }
}
