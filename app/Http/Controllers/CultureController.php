<?php

namespace App\Http\Controllers;

use App\cultureCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
        $validatedData      =  $this->validateNewCategoryRequest($request);
        if (isset($validatedData['categoryId'])) {
            $newCategory        = $this->editExistingCategory($validatedData);
        }else{
            $newCategory        =  $this->createNewCategoryFromData($validatedData);
        }

        if ($this->isNewCategoryAddedToDatabase($newCategory)) {
            return response()->json(['action' => 'savedData'], 200);
        }
    }



    // Private Functions

    private function validateNewCategoryRequest(Request $categoryData) : array
    {
        $validatedRequest = $categoryData->validate([
            'categoryName'      =>  ['required','string'],
            'categoryAttr.*'    =>  ['required','string'],
            'categoryId'        =>  ['exists:culture_categories,id']
        ]);
        return $validatedRequest;
    }

    private function createNewCategoryFromData(Array $data) : cultureCategory
    {
        $newCategory = new cultureCategory();

        $newCategory->name          = $data['categoryName'];
        $newCategory->name_slug     = Str::slug($data['categoryName']);
        $newCategory->attributes    = json_encode($data['categoryAttr']);
        $newCategory->user_id       = Auth::id();

        return $newCategory;
    }

    private function editExistingCategory(Array $data) : cultureCategory
    {
        $existingCategory = cultureCategory::find($data['categoryId']);

        if ($existingCategory) {

            $existingCategory->name          = $data['categoryName'];
            $existingCategory->name_slug     = Str::slug($data['categoryName']);
            $existingCategory->attributes    = json_encode($data['categoryAttr']);
            $existingCategory->user_id       = Auth::id();
    
            return $existingCategory;
        }
    }

    private function isNewCategoryAddedToDatabase(cultureCategory $newCategory) : bool
    {
        if ($newCategory->save()) {
            return true;
        }else{
            return false;
        }
    }
}
