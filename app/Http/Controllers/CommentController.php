<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Comment;
use App\Post;

use Auth;

class CommentController extends Controller
{

    public function __construct() {
        $this->middleware('verified');
    }

    public function getComments(Request $request, Post $post)
    {
        if ($request->ajax()) {
            $html = view('partials.ajaxWallComment')->withComments($post->comments)->render();
            return response()->json(['status' => 'success', 'html' => $html], 200);
        }
    }
    
    public function newComment(Request $request)
    {
        if ($request->ajax()) {
            $kek = $request->all();
            $request->validate([
                'data.*.value' => ['string','max:255'],
                'postId'       => ['exists:posts,id']
            ]);

            $comment = new Comment;

            $comment->message   = $request->data[0]['value'];
            $comment->author_id = Auth::id();
            $comment->post_id = $request->postId;  

            $comment->save();
            $html = view('partials.ajaxWallComment')->withComments([$comment])->render();
        }
        return response()->json(['status' => 'success','html' => $html], 200);
    }

    public function deleteComment(Request $request)
    {
        # code...
    }
}
