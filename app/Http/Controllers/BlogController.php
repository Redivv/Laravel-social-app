<?php

namespace App\Http\Controllers;

use App\blogCategory;
use App\blogPost;
use App\Jobs\newBlogPostNotifications;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function index(){
        $posts = blogPost::all();
        return view('blogMainPage')->withPosts($posts);
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
        $postId = $request->elementId;

        blogPost::find($postId)->delete();
        return response()->json(['action' => "deletePostItem"], 200);
    }
}
