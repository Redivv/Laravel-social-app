<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Auth;

use Illuminate\Http\Request;

use Conner\Tagging\Model\Tag;
use App\City;
use App\User;

class AjaxTagsController extends Controller
{
    public function addNew(Request $request)
    {
        //if request done via ajax
        if ($request->ajax()) {
            //validating sent data
            $data = $request->validate([
                'tag'  =>  ['required', 'string', 'max:255']
            ]);
            $user = Auth::user();
            //saving tags for user
            $user->tag($data['tag']);
            //dunno
            $tagsList = $user->tagNames();
            //if tagList ends without tag name given bu user, fuck'em up
            if (end($tagsList) != Str::title($data['tag'])) {
                return response()->json(['status' => 'repeat'],400);
            }else{
                //else we render html partial using user tag array
                $html = view('partials.profile.tagListEdit')->withTags(array('tag' => $data['tag']))->render();
                //and return response using json, that includes status(succes) and our html, with code 200 (succes)
                return response()->json(['status' => 'success', 'html' => $html],200);
            }
        }
    }

    public function deleteTag(Request $request)
    {
        $data = $request->validate([
            'tag'  =>  ['required', 'string', 'max:255']
        ]);
        $user = Auth::user();
        $tagsList = $user->tagNames();
        if (array_search(Str::title($data['tag']),$tagsList) !== false) {
            $user->untag($data['tag']);
            return response()->json(['status' => 'success'], 200);
        }else{
            return response()->json(['status' => 'not-found'], 406);
        }
    }

    public function autocompleteHobby(Request $request)
    {
        $request->validate([
            'term'  =>  ['required', 'string', 'max:255']
        ]);
        $search = Str::slug($request->get('term'));
    
        $result = Tag::where('slug', 'LIKE', $search.'%')->get();

        return response()->json($result);
            
    } 

    public function autocompleteCity(Request $request)
    {
        $request->validate([
            'term'  =>  ['required', 'string', 'max:255']
        ]);
        $search = Str::slug($request->get('term'));
    
        $result = City::where('name_slug', 'LIKE', $search.'%')->get();

        return response()->json($result);
            
    } 

    public function autocompleteUser(Request $request)
    {
        $request->validate([
            'term'  =>  ['required', 'string', 'max:255']
        ]);
        $search = $request->get('term');
    
        $result = User::where('name', 'LIKE', $search.'%')->whereNotIn('id',[Auth::id()])->get();

        return response()->json($result);
            
    } 
}
