<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use App\cultureItem;
use App\cultureCategory;
use App\Jobs\newCultureItemNotifications;
use App\Partner;
use App\User;

class CultureController extends Controller
{
    public function index()
    {
        $categories = cultureCategory::all();

        $newestItems = cultureItem::orderBy('created_at', 'desc')->take(3)->get();

        $suggestedItems = [];
        if (Auth::check()) {
            $suggestedItems['items'] = cultureItem::withAnyTag(Auth::user()->tagNames())->inRandomOrder()->take(3)->get();
            if (count($suggestedItems['items']) < 1) {
                $suggestedItems['type'] = 'Likes';
                $suggestedItems['items'] = $this->getMostLikedItemsTake(3);
            } else {
                $suggestedItems['type'] = 'Tag';
            }
        } else {
            $suggestedItems['type'] = 'Likes';
            $suggestedItems['items'] = $this->getMostLikedItemsTake(3);
        }

        return view('cultureMainPage')->withCategories($categories)->withNew($newestItems)->withSuggest($suggestedItems);
    }

    public function searchResults(Request $request)
    {
        $categories = cultureCategory::all();

        $validatedRequest = $this->validateCultureSearch($request);

        $searchResults = $this->getCultureSearchResults($validatedRequest);

        return view('cultureSearchResults')->withResults($searchResults)->withCategories($categories);
    }

    public function item(cultureItem $cultureItem)
    {
        $itemTags = $cultureItem->tagNames();
        $similarEntries = cultureItem::withAnyTag($itemTags)->whereNotIn('id', [$cultureItem->id])->inRandomOrder()->take(10)->get()->sortByDesc(function ($product) use ($itemTags) {
            return array_intersect($itemTags, $product->tagNames());
        })->take(4);

        if (count($similarEntries) <= 0) {
            $similarEntries = cultureItem::where('id', '!=', $cultureItem->id)
                ->where('category_id', '=', $cultureItem->category_id)
                ->take(4)
            ->get();
        }

        return view('cultureItem')->withCultureItem($cultureItem)->withSimilarEntries($similarEntries);
    }

    public function newCategory(Request $request)
    {
        $validatedData = $this->validateNewCategoryRequest($request);
        if (isset($validatedData['categoryId'])) {
            $newCategory = $this->editExistingCategory($validatedData);
        } else {
            $newCategory = $this->createNewCategoryFromData($validatedData);
        }

        if ($this->wasNewCategoryAddedToDatabase($newCategory)) {
            return response()->json(['action' => 'savedData'], 200);
        }
    }

    public function newItem(Request $request)
    {
        $validatedData = $this->validateNewItemRequest($request);
        if (isset($validatedData['itemId'])) {
            $newItem = $this->editExistingItem($validatedData);
        } else {
            $newItem = $this->createNewItemFromData($validatedData);
        }

        if ($this->wasNewItemAddedToDatabase($newItem)) {
            $this->tagNewItemWithData($newItem, $validatedData);

            $users = User::all();

            if (!isset($validatedData['itemId'])) {
                newCultureItemNotifications::dispatch($users, $newItem);
            }

            return response()->json(['action' => 'savedData'], 200);
        }
    }

    public function deleteItem(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'elementId' => ['required','numeric','exists:culture_items,id'],
            ]);
            if (cultureItem::where('id', $request->elementId)->delete()) {
                return response()->json(['action' => 'deleteCultureItem'], 200);
            }
        }
    }

    public function partners()
    {
        $partners = Partner::all();

        return view('partners')->withPartners($partners);
    }

    public function getReview(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'data' => ['numeric','exists:culture_items,id'],
            ]);
            $review = trim(cultureItem::find($request->data)->review);

            return response()->json(['action' => 'displayReview','html' => $review], 200);
        }
    }

    // Private Functions

    private function validateCultureSearch(Request $data) : Request
    {
        $validOptions = ['lettersSort','likesSort','dateSort'];
        $validDirs = ['asc','desc'];
        $categoriesNames = cultureCategory::all()->pluck('name')->toArray();

        $data->validate([
            'titleName' => ['present','string','nullable'],
            'itemTags.*' => ['string','nullable'],
            'options' => ['required','string',Rule::in($validOptions)],
            'sortOptionsDir' => ['required','string',Rule::in($validDirs)],
            'searchCategory' => ['string',Rule::in($categoriesNames)],
        ]);

        return $data;
    }

    private function getCultureSearchResults(Request $criteria) : LengthAwarePaginator
    {
        if (empty($criteria->titleName)) {
            $searchResults = cultureItem::select('*');
        } else {
            $searchResults = cultureItem::where('name_slug', 'like', Str::slug($criteria->titleName));
        }

        if (isset($criteria->searchCategory)) {
            $catId = cultureCategory::where('name_slug', $criteria->searchCategory)->get()->pluck('id')->toArray()[0];

            $searchResults = $searchResults->where('category_id', $catId);
        }

        if (isset($criteria->itemTags[0])) {
            $searchResults = $searchResults->withAnyTag($criteria->itemTags);
        }

        if ($criteria->options === 'likesSort') {
            $searchLikes = $searchResults->get();

            if ($criteria->sortOptionsDir == 'asc') {
                $searchLikes = $searchLikes->sortByDesc(function ($item, $key) {
                    return $item->likeCount;
                })->pluck('id')->toArray();
            } else {
                $searchLikes = $searchLikes->sortBy(function ($item, $key) {
                    return $item->likeCount;
                })->pluck('id')->toArray();
            }

            $orderedIds = implode(',', $searchLikes);

            $searchResults = $searchResults
                ->orderByRaw(\DB::raw('FIELD(culture_items.id, ' . $orderedIds . ' )'))
                ->paginate(5);
        } else {
            switch ($criteria->options) {
                case 'lettersSort':
                    $searchResults = $searchResults->orderBy('name_slug', $criteria->sortOptionsDir);

                    break;
                case 'dateSort':
                    $searchResults = $searchResults->orderBy('created_at', $criteria->sortOptionsDir);

                    break;
            }

            $searchResults = $searchResults->paginate(5);
        }

        return $searchResults;
    }

    private function getMostLikedItemsTake(int $amount = 10): Collection
    {
        $allItems = cultureItem::all();
        $mostLikedItems = $allItems->sortByDesc(function ($item, $key) {
            return $item->likeCount;
        })->take($amount);

        return $mostLikedItems;
    }

    private function validateNewCategoryRequest(Request $categoryData): array
    {
        $validatedRequest = $categoryData->validate(
            [
                'categoryName' => ['required', 'string'],
                'categoryAttr.*' => ['required', 'string'],
                'categoryId' => ['numeric', 'exists:culture_categories,id'],
            ]
        );

        return $validatedRequest;
    }

    private function validateNewItemRequest(Request $itemData): array
    {
        $validatedRequest = $itemData->validate([
            'itemCategory' => ['required', 'numeric', 'exists:culture_categories,id'],
            'itemName' => ['required', 'string'],
            'itemAttr.*' => ['nullable', 'string'],
            'itemTags.*' => ['nullable', 'string'],
            'itemDesc' => ['required', 'string'],
            'itemReview' => ['nullable', 'string'],
            'itemThumbnail' => ['nullable', 'file', 'image', 'max:10000', 'mimes:jpeg,png,jpg,gif,svg'],
            'itemImages.*' => ['nullable', 'file', 'image', 'max:10000', 'mimes:jpeg,png,jpg,gif,svg'],
            'itemId' => ['numeric', 'exists:culture_items,id'],
            'noImages' => ['filled', Rule::in(['true'])],
        ]);

        return $validatedRequest;
    }

    private function createNewCategoryFromData(array $data): cultureCategory
    {
        $newCategory = new cultureCategory();

        $newCategory->name = $data['categoryName'];
        $newCategory->name_slug = Str::slug($data['categoryName']);
        $newCategory->attributes = json_encode($data['categoryAttr']);
        $newCategory->user_id = Auth::id();

        return $newCategory;
    }

    private function createNewItemFromData(array $data): cultureItem
    {
        $newItem = new cultureItem();

        $newItem->category_id = intVal($data['itemCategory']);

        $newItem->name = $data['itemName'];
        $newItem->name_slug = Str::slug($data['itemName']);

        $newItem->description = $data['itemDesc'];

        if (isset($data['itemAttr'])) {
            $newItem->attributes = json_encode($data['itemAttr']);
        }

        if (isset($data['itemThumbnail'])) {
            $newItem->thumbnail = $this->handleImages([$data['itemThumbnail']]);
        }

        if (isset($data['itemImages'])) {
            $newItem->pictures = $this->handleImages($data['itemImages']);
        }

        if (isset($data['itemReview'])) {
            $newItem->review = $data['itemReview'];
        }

        $newItem->user_id = Auth::id();

        return $newItem;
    }

    private function editExistingCategory(array $data): cultureCategory
    {
        $existingCategory = cultureCategory::find($data['categoryId']);

        if ($existingCategory) {
            $existingCategory->name = $data['categoryName'];
            $existingCategory->name_slug = Str::slug($data['categoryName']);
            $existingCategory->attributes = json_encode($data['categoryAttr']);
            $existingCategory->user_id = Auth::id();

            return $existingCategory;
        }
    }

    private function editExistingItem(array $data): cultureItem
    {
        $item = cultureItem::find($data['itemId']);

        $item->category_id = intVal($data['itemCategory']);

        $item->name = $data['itemName'];
        $item->name_slug = Str::slug($data['itemName']);

        $item->description = $data['itemDesc'];

        if (isset($data['itemAttr'])) {
            $item->attributes = json_encode($data['itemAttr']);
        } else {
            $item->attributes = null;
        }

        if (isset($data['itemThumbnail'])) {
            $item->thumbnail = $this->handleImages([$data['itemThumbnail']]);
        }

        if (isset($data['itemImages'])) {
            $item->pictures = $this->handleImages($data['itemImages']);
        } elseif (isset($data['noImages'])) {
            $item->pictures = null;
        }

        if (isset($data['itemReview'])) {
            $item->review = $data['itemReview'];
        } else {
            $item->review = null;
        }

        return $item;
    }

    private function wasNewCategoryAddedToDatabase(cultureCategory $newCategory): bool
    {
        return $newCategory->save();
    }

    private function wasNewItemAddedToDatabase(cultureItem $newItem): bool
    {
        return $newItem->save();
    }

    private function tagNewItemWithData(cultureItem $newItem, array $data): void
    {
        $newItem->untag();
        if (isset($data['itemTags'])) {
            $newItem->tag($data['itemTags']);
        }
    }

    private function handleImages(array $images): string
    {
        $pictures_json = [];
        foreach ($images as $picture) {
            $imageName = hash_file('haval160,4', $picture->getPathname()) . '.' . $picture->getClientOriginalExtension();
            $picture->move(public_path('img/culture-pictures'), $imageName);
            $pictures_json[] = $imageName;
        }

        return json_encode($pictures_json);
    }
}
