<?php

namespace App\Http\Controllers;

use App\cultureCategory;
use App\cultureItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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

        if ($this->wasNewCategoryAddedToDatabase($newCategory)) {
            return response()->json(['action' => 'savedData'], 200);
        }
    }

    public function newItem(Request $request)
    {
        $validatedData          =   $this->validateNewItemRequest($request);
        if (isset($validatedData['itemId'])) {
            $newItem                = $this->editExistingItem($validatedData);
        }else{
            $newItem                =   $this->createNewItemFromData($validatedData);
        }

        if ($this->wasNewItemAddedToDatabase($newItem)) {
            $this->tagNewItemWithData($newItem,$validatedData);
            return response()->json(['action' => 'savedData'], 200);
        }
    }



    // Private Functions

    private function validateNewCategoryRequest(Request $categoryData) : array
    {
        $validatedRequest = $categoryData->validate([
            'categoryName'      =>  ['required','string'],
            'categoryAttr.*'    =>  ['required','string'],
            'categoryId'        =>  ['numeric','exists:culture_categories,id']
        ]);
        return $validatedRequest;
    }

    private function validateNewItemRequest(Request $itemData) : array
    {
        $validatedRequest = $itemData->validate([
            'itemCategory'      => ['required','numeric','exists:culture_categories,id'],
            'itemName'          => ['required','string'],
            'itemAttr.*'        => ['nullable','string'],
            'itemTags.*'        => ['nullable','string'],
            'itemDesc'          => ['required','string'],
            'itemReview'        => ['nullable','string'],
            'itemThumbnail'     => ['nullable', 'file', 'image', 'max:10000', 'mimes:jpeg,png,jpg,gif,svg'],
            'itemImages.*'      => ['nullable', 'file', 'image', 'max:10000', 'mimes:jpeg,png,jpg,gif,svg'],
            'itemId'            => ['numeric','exists:culture_items,id'],
            'noImages'          => ['filled', Rule::in(['true'])]
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

    private function createNewItemFromData(Array $data) : cultureItem
    {
        $newItem = new cultureItem();

        $newItem->category_id = intVal($data['itemCategory']);

        $newItem->name          = $data['itemName'];
        $newItem->name_slug     = Str::slug($data['itemName']);

        $newItem->description = $data['itemDesc'];

        if (isset($data['itemAttr'])) {
            $newItem->attributes    = json_encode($data['itemAttr']);
        }

        if(isset($data['itemThumbnail'])){
            $newItem->thumbnail = $this->handleImages([$data['itemThumbnail']]);
        }

        if(isset($data['itemImages'])){
            $newItem->pictures = $this->handleImages($data['itemImages']);
        }

        if (isset($data['itemReview'])) {
            $newItem->review = $data['itemReview'];
        }

        $newItem->user_id = Auth::id();

        return $newItem;
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

    private function editExistingItem(Array $data) : cultureItem
    {
        $item = cultureItem::find($data['itemId']);

        $item->category_id = intVal($data['itemCategory']);

        $item->name          = $data['itemName'];
        $item->name_slug     = Str::slug($data['itemName']);

        $item->description = $data['itemDesc'];

        if (isset($data['itemAttr'])) {
            $item->attributes    = json_encode($data['itemAttr']);
        }else{
            $item->attributes    = null;
        }

        if(isset($data['itemThumbnail'])){
            $item->thumbnail = $this->handleImages([$data['itemThumbnail']]);
        }

        if(isset($data['itemImages'])){
            $item->pictures = $this->handleImages($data['itemImages']);
        }elseif(isset($data['noImages'])){
            $item->pictures = null;
        }

        if (isset($data['itemReview'])) {
            $item->review = $data['itemReview'];
        }else{
            $item->review = null;
        }

        return $item;
    }

    private function wasNewCategoryAddedToDatabase(cultureCategory $newCategory) : bool
    {
        return $newCategory->save();
    }

    private function wasNewItemAddedToDatabase(cultureItem $newItem) : bool
    {
        return $newItem->save();
    }

    private function tagNewItemWithData(cultureItem $newItem, array $data) : void
    {
        $newItem->untag();
        if (isset($data['itemTags'])) {
            $newItem->tag($data['itemTags']);
        }
    }

    private function handleImages(Array $images) : string
    {
        $pictures_json = array();
        foreach ($images as $picture) {
            $imageName = hash_file('haval160,4',$picture->getPathname()).'.'.$picture->getClientOriginalExtension();
            $picture->move(public_path('img/culture-pictures'), $imageName);
            $pictures_json[] = $imageName;
        }
        return json_encode($pictures_json);

    }
}
