<?php

namespace App\Http\Controllers;


use App\blogCategory;
use App\blogPost;
use App\Jobs\newBlogPostNotifications;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function index(Request $request){

        if($request->ajax()){
            $posts = $this->getSortResults($request);
            $pagiStop = count($posts) == 5;
            $html = view('partials.blog.posts')->withPosts($posts)->render();

            return response()->json(['status' => 'success', 'html' => $html, 'stop' => $pagiStop], 200);
        }else{
            $categories = blogCategory::all();

            $posts = $this->getSortResults($request);
    
            $events = [
                0 => [
                    'title' => 'Event Title1',
                    'start' => '2020-03-17T13:13:55.008',
                    'end'   => '2020-03-19T13:13:55.008',
                    'url'   => 'http://google.com/'
                ]
            ];
            $events = json_encode($events);
    
            return view('blogMainPage')->withEvents($events)->withPosts($posts)->withCats($categories);
        }
    }

    private function getSortResults(Request $request)
    {
        $this->validateSortData($request);
        return $this->getSortedPosts($request->all());
    }

    private function validateSortData(Request $data) : void
    {
        $data->validate([
            'sortRange'     => ['string',Rule::in(['week','month','all'])],
            'sortCrit'      => ['string',Rule::in(['date','likes'])],
            'postCategory'  => ['exists:blog_categories,id','string'],
            'postTags.*'    => ['string'],
            'sortDir'       => ['string',Rule::in(['asc','desc'])],
            'pagi'          => ['numeric','min:2']
        ]);
    }

    private function getSortedPosts(array $criteria) : Collection
    {

        $posts = blogPost::select('*');
        
        if (isset($criteria['postCategory'])) {
            $posts = $posts->where('category_id',$criteria['postCategory']);
        }

        if (isset($criteria['postTags'])) {
            $posts = $posts->withAnyTag($criteria['postTags']);
        }

        if ( isset($criteria['sortCrit'])  && isset($criteria['sortDir'])) {
            if ($criteria['sortCrit'] == "date") {
                $posts = $posts->orderBy('created_at',$criteria['sortDir']);
            }
        }else{
            $posts = $posts->orderBy('created_at','desc');
        }
        
        if (isset($criteria['pagi'])) {
            if (isset($criteria['sortCrit'])) {
                if($criteria['sortCrit'] == 'likes'){
                    if (isset($criteria['sortRange'])) {
                        $range = $criteria['sortRange'];
                    }else{
                        $range = 'week';
                    }
                    switch ($range) {
                        case 'week':
                            $rangeTime = Carbon::now()->subWeek()->toDateTimeString();
                            $posts = $posts->where('created_at','>',$rangeTime)->skip(5*(intVal($criteria['pagi']) - 1))->take(5)->get();
                            break;
                        case 'month':
                            $rangeTime = Carbon::now()->subMonth()->toDateTimeString();
                            $posts = $posts->where('created_at','>',$rangeTime)->skip(5*(intVal($criteria['pagi']) - 1))->take(5)->get();
                            break;
                        case 'all':
                            $posts = $posts->skip(5*(intVal($criteria['pagi']) - 1))->take(5)->get();
                            break;
                    }
                    
                    if ($criteria['sortDir'] == "desc") {
    
                        $posts = $posts->sortByDesc(function($post,$key){
                            return $post->likeCount;
                        });
                    }else{
                        $posts = $posts->sortBy(function($post,$key){
                            return $post->likeCount;
                        });
                    }
                }
            }else{
                $posts = $posts->skip(5*(intVal($criteria['pagi']) - 1))->take(5)->get();
            }
        }else{
            if (isset($criteria['sortCrit'])) {
                if($criteria['sortCrit'] == 'likes'){
                    if (isset($criteria['sortRange'])) {
                        $range = $criteria['sortRange'];
                    }else{
                        $range = 'week';
                    }
                    switch ($range) {
                        case 'week':
                            $rangeTime = Carbon::now()->subWeek()->toDateTimeString();
                            $posts = $posts->where('created_at','>',$rangeTime)->take(5)->get();
                            break;
                        case 'month':
                            $rangeTime = Carbon::now()->subMonth()->toDateTimeString();
                            $posts = $posts->where('created_at','>',$rangeTime)->take(5)->get();
                            break;
                        case 'all':
                            $posts = $posts->take(5)->get();
                            break;
                    }
                    
                    if ($criteria['sortDir'] == "desc") {
    
                        $posts = $posts->sortByDesc(function($post,$key){
                            return $post->likeCount;
                        });
                    }else{
                        $posts = $posts->sortBy(function($post,$key){
                            return $post->likeCount;
                        });
                    }
                }
            }else{
                $posts = $posts->take(5)->get();
            }
        }
        return $posts;


    }

    public function post(blogPost $blogPost)
    {
        return view('blogPostPage')->withPost($blogPost);
    }

    public function newPost(Request $request)
    {
        $validatedData          =   $this->validateNewPostData($request);
        if (isset($validatedData['postId'])) {
            $newPost                = $this->editExistingPost($validatedData);
        } else {
            $newPost                =   $this->createNewpostFromData($validatedData);
        }

        if ($this->wasNewPostAddedToDatabase($newPost)) {
            $this->tagNewPostWithData($newPost, $validatedData);

            $users = User::all();

            newBlogPostNotifications::dispatch($users,$newPost);

            return response()->json(['action' => 'savedData'], 200);
        }
    }

    private function validateNewPostData(Request $postData): array
    {
        $validatedRequest = $postData->validate([
            'postCategory'      => ['required', 'string'],
            'postName'          => ['required', 'string'],
            'postTags.*'        => ['nullable', 'string'],
            'postDesc'          => ['required', 'string'],
            'postThumbnail'     => ['nullable', 'file', 'image', 'max:10000', 'mimes:jpeg,png,jpg,gif,svg'],
            'postId'            => ['numeric', 'exists:blog_posts,id'],
            'noImages'          => ['filled', Rule::in(['true'])]
        ]);
        return $validatedRequest;
    }

    private function editExistingPost(array $data): blogPost
    {
        $post = blogPost::find($data['postId']);
        $category = $this->getPostCategory($data['postCategory']);

        if ($category === null) {
            $category = $this->createNewCategoryByName($data['postCategory']);
        }

        $post->category_id = $category->id;

        $post->name          = $data['postName'];
        $post->name_slug     = Str::slug($data['postName']);

        $post->description = $data['postDesc'];

        if (isset($data['postThumbnail'])) {
            $post->thumbnail = $this->handleImages([$data['postThumbnail']]);
        }

        return $post;
    }

    private function handleImages(array $images): string
    {
        $pictures_json = array();
        foreach ($images as $picture) {
            $imageName = hash_file('haval160,4', $picture->getPathname()) . '.' . $picture->getClientOriginalExtension();
            $picture->move(public_path('img/blog-pictures'), $imageName);
            $pictures_json[] = $imageName;
        }
        return json_encode($pictures_json);
    }

    private function createNewPostFromData(array $data): blogPost
    {
        $newPost = new blogPost();
        $category = $this->getPostCategory($data['postCategory']);

        if ($category === null) {
            $category = $this->createNewCategoryByName($data['postCategory']);
        }
        
        $newPost->category_id = $category->id;

        $newPost->name          = $data['postName'];
        $newPost->name_slug     = Str::slug($data['postName']);

        $newPost->description = $data['postDesc'];

        if (isset($data['postThumbnail'])) {
            $newPost->thumbnail = $this->handleImages([$data['postThumbnail']]);
        }

        $newPost->author_id = Auth::id();

        return $newPost;
    }

    private function getPostCategory(string $categoryName)
    {
        $category = blogCategory::where('name_slug',Str::slug($categoryName))->first();
        return $category;
    }

    private function createNewCategoryByName(string $categoryName) : blogCategory
    {
        $newCategory = new blogCategory();

        $newCategory->name = $categoryName;
        $newCategory->name_slug = Str::slug($categoryName);

        $newCategory->save();
        
        return $newCategory;
    }

    private function wasNewPostAddedToDatabase(blogPost $newPost): bool
    {
        return $newPost->save();
    }

    private function tagNewPostWithData(blogPost $newPost, array $data): void
    {
        $newPost->untag();
        if (isset($data['postTags'])) {
            $newPost->tag($data['postTags']);
        }
    }

    public function deletePost(Request $request)
    {
        if ($request->ajax()) {
            $postId = $request->elementId;
    
            blogPost::find($postId)->delete();
            return response()->json(['action' => "deletePostItem"], 200);
        }
    }

    public function likePost(Request $request)
    {
        $request->validate([
            'id'    => ['exists:blog_posts,id']
        ]);

        $post = blogPost::find($request->id);

        if ($post->liked()) {
            $post->unlike();
        }else{
            $post->like();
        }

        return response()->json(['status' => 'success'], 200);
    }
}
