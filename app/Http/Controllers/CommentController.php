<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Comment;
use App\Post;

use App\Notifications\SystemNotification;

use Auth;

class CommentController extends Controller
{

    public function __construct() {
        $this->middleware('verified');
    }

    public function getComments(Request $request, Post $post)
    {
        if ($request->ajax()) {

            $request->validate([
                'pagi'  => 'numeric'
            ]);

            $commentsAmount = count($post->comments);

            $comments = Comment::where('post_id',$post->id)->whereNull('parent_id')->take(5)->skip(5*$request->pagi)->orderBy('created_at','desc')->get();

            $html = view('partials.ajaxWallComment')->withComments($comments)->withId($post->id)->withPagi($request->pagi+1)->withCommentsAmount($commentsAmount - count($comments))->render();
            return response()->json(['status' => 'success', 'html' => $html], 200);
        }
    }

    public function getReplies(Request $request, Comment $comment)
    {
        if ($request->ajax()) {

            $request->validate([
                'pagi'  => 'numeric'
            ]);

            $replies = Comment::where('parent_id',$comment->id)->take(5)->skip(5*$request->pagi)->orderBy('created_at','desc')->get();

            $html = view('partials.wallReplies')->withReplies($replies)->withId($comment->id)->withPagi($request->pagi+1)->render();
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

                if ($parentComment->author_id != Auth::id()) {
                    $parentComment->user->notify(new SystemNotification(__('nav.replyNot'),'info','_user_home#com-',$parentComment->id, 'newRep'));
                }

                $html = view('partials.ajaxWallReply')->withComment($newComment)->render();
            }else{

                $post = Post::find($request->postId);
                $newComment->post_id = $request->postId;  
                
                $newComment->save();

                if ($post->user_id != Auth::id()) {
                    $post->user->notify(new SystemNotification(__('nav.commentNot'),'info','_user_home#post',$newComment->post->id, 'newCom'));
                }

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

                DB::table('likeable_like_counters')->where('likeable_id',$request->id)->delete();
                DB::table('likeable_likes')->where('likeable_id',$request->id)->delete();

                return response()->json(['status' => 'success'], 200);
            }
            return response()->json(['status' => 'error'], 400);
        }
    }

    public function likeComment(Request $request)
    {
        if($request->ajax()){
            $request->validate([
                'commentId' => 'exists:comments,id'
            ]);

            $comment = Comment::find($request->commentId);

            if ($comment->liked()) {
                $comment->unlike();
            }else{
                $comment->like();

                if ($comment->author_id != Auth::id()) {
                    $comment->user->notify(new SystemNotification(__('nav.likeComNot'),'info','_user_home#com-',$comment->id, 'likeCom'));
                }
            }

            return response()->json(['status' => 'success'], 200);
        }

    }
}
