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
        $validatedData = $this->validateNewCategoryRequest($request);
        $this->createNewCategoryFromData($validatedData);
        return response()->json(['status' => 'success'], 200);
    }




    // Private Functions

    private function validateNewCategoryRequest(Request $categoryData) : array
    {
        $validatedRequest = $categoryData->validate([
            'categoryName'      =>  ['max:1','numeric'],
            'categoryAttr.*'    =>  ['required','string']  
        ]);
        return $validatedRequest;
    }

    private function createNewCategoryFromData(Array $data) : void
    {
        $kek = $data;
    }
}
