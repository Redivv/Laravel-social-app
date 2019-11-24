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

    public function getReplies(Request $request, Comment $comment)
    {
        if ($request->ajax()) {
            $html = view('partials.wallReplies')->withReplies($comment->replies)->render();
            return response()->json(['status' => 'success', 'html' => $html], 200);
        }
    }
    
    public function newComment(Request $request)
    {
        if ($request->ajax()) {
            $kek = $request->all();
            $request->validate([
                'data.*.value' => ['string','max:255'],
                'postId'       => ['exists:posts,id','nullable','required_without:parentId'],
                'parentId'     => ['exists:comments,id','required_without:postId']
            ]);

            $newComment = new Comment;

            $newComment->message   = $request->data[0]['value'];
            $newComment->author_id = Auth::id();
            if (isset($request->parentId)) {
                $parentComment = Comment::find($request->parentId);
                
                $newComment->post_id = $parentComment->post_id;
                $newComment->parent_id = $parentComment->id;
                
                $newComment->save();

                $html = view('partials.ajaxWallReply')->withComment($newComment)->render();
            }else{
                $newComment->post_id = $request->postId;  
                
                $newComment->save();

                $html = view('partials.ajaxWallComment')->withComments([$newComment])->render();
            }
        }
        return response()->json(['status' => 'success','html' => $html], 200);
    }

    public function editComment(Request $request)
    {
        if ($request->ajax()) {

            $request->validate([
                'data.*.value' => ['string','max:255'],
                'commentId'    => ['exists:comments,id','nullable']
            ]);

            $comment = Comment::where('id',$request->commentId)->where('author_id',Auth::id())->first();

            $comment->message = $request->data[0]['value'];
            if ($comment->update()) {
                $html = view('partials.ajaxWallCommentSingle')->withComments([$comment])->render();
                return response()->json(['status' => 'success','html' => $html], 200);
            }
        }
    }

    public function deleteComment(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'id'    => ['required','exists:comments']
            ]);
            
            if(Comment::where('id',$request->id)->where('author_id',Auth::id())->delete()){
                return response()->json(['status' => 'success'], 200);
            }
            return response()->json(['status' => 'error'], 400);
        }
    }
}
