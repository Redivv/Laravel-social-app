<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Comment;
use App\cultureComment;
use App\cultureItem;
use App\Post;
use App\User;

use App\Notifications\SystemNotification;
use Illuminate\Support\Facades\Notification;

use Auth;

class CommentController extends Controller
{

    public function __construct() {
        $this->middleware('verified');
    }
    
    public function newComment(Request $request)
    {
        if ($request->ajax()) {

            if ($request->commentType === "culture") {
                $request->validate([
                    'data.0.value' => ['string'],
                    'itemId'       => ['exists:culture_items,id','nullable','required_without:parentId'],
                    'parentId'     => ['exists:culture_comments,id','required_without:itemId'],
                ]);

                $newComment = new cultureComment;
            }else{
                $request->validate([
                    'data.0.value' => ['string'],
                    'postId'       => ['exists:posts,id','nullable','required_without:parentId'],
                    'parentId'     => ['exists:comments,id','required_without:postId'],
                ]);

                $newComment = new Comment;
            }

            $newComment->message   = $request->data[0]['value'];
            $newComment->author_id = Auth::id();

            $taggedUsers        = $request->data;
            $taggedUsersArray   = null;

            unset($taggedUsers[0]);
            
            
            if ($taggedUsers) {
                $taggedUsers_json = array();
                $taggedUsersArray = array();
                foreach ($taggedUsers as $tagged) {
                    $user = User::find($tagged['value']);
                    $taggedUsers_json[] = $user->name;
                    $taggedUsersArray[] = $user;
                }
                $taggedUsers_json = json_encode($taggedUsers_json);
                $newComment->tagged_users = $taggedUsers_json;
            }

            if (isset($request->parentId)) {
                
                if ($request->commentType === "culture") {
                    $parentComment = cultureComment::find($request->parentId);
                    
                    $newComment->item_id = $parentComment->item_id;
                    $newComment->parent_id = $parentComment->id;
                    
                    $newComment->save();

                    if ($parentComment->author_id != Auth::id()) {
                        $parentComment->user->notify(new SystemNotification(__('nav.replyNot',[],$parentComment->user->locale),'info','_culture_',$parentComment->item->name_slug,'#com-'.$parentComment->id, 'newCultRep'));
                    }

                    if ($taggedUsers) {
                        foreach ($taggedUsersArray as $taggedUser) {
                            $taggedUser->notify(new SystemNotification(__('nav.taggedInComment',[],$taggedUser->locale),'success','_culture_',$parentComment->item->name_slug,'#com-'.$parentComment->id, 'tagCultCom'));
                        }
                    }

                    $html = view('partials.culture.ajaxCommentReply')->withComment($newComment)->render();
                }else{
                    $parentComment = Comment::find($request->parentId);

                    if ($parentComment->post->canBeSeen()) {
                    
                        $newComment->post_id = $parentComment->post_id;
                        $newComment->parent_id = $parentComment->id;
                        
                        $newComment->save();
    
                        if ($parentComment->author_id != Auth::id()) {
                            $parentComment->user->notify(new SystemNotification(__('nav.replyNot',[],$parentComment->user->locale),'info','_user_home_post_',$parentComment->post->id,'#com-'.$parentComment->id, 'newRep'));
                        }
    
                        if ($taggedUsers) {
                            foreach ($taggedUsersArray as $taggedUser) {
                                $taggedUser->notify(new SystemNotification(__('nav.taggedInComment',[],$taggedUser->locale),'success','_user_home_post_',$parentComment->post->id,'#com-'.$parentComment->id, 'tagCom'));
                            }
                        }
    
                        $html = view('partials.home.ajax.ajaxWallReply')->withComment($newComment)->render();
                    }else{
                        return response()->json(['status' => 'error', 'message' => 'You cannot see this post'], 400);
                    }
                }
            }else{
                
                if ($request->commentType === "culture") {
                    $item = cultureItem::find($request->itemId);
                    
                    $newComment->item_id = $request->itemId;  
                    
                    $newComment->save();

                    if ($item->user_id != Auth::id()) {
                        $item->user->notify(new SystemNotification(__('nav.commentNot',[],$item->user->locale),'info','_culture_',$newComment->item->name_slug,'#com-'.$newComment->id, 'newCultCom'));
                    }
                    
                    if ($taggedUsers) {
                        if ($taggedUsers[1]['name'] != 'noTags') {
                            foreach ($taggedUsersArray as $taggedUser) {
                                $taggedUser->notify(new SystemNotification(__('nav.taggedInComment',[],$taggedUser->locale),'success','_culture_',$item->name_slug, '#com-'.$newComment->id, 'tagCultCom'));
                            }
                        }
                    }

                    $html = view('partials.culture.ajaxCommentSingle')->withComments([$newComment])->render();
                }else{
                    $post = Post::find($request->postId);
    
                    if ($post->canBeSeen()) {
                    
                        $newComment->post_id = $request->postId;  
                        
                        $newComment->save();
    
                        if ($post->user_id != Auth::id()) {
                            $post->user->notify(new SystemNotification(__('nav.commentNot',[],$post->user->locale),'info','_user_home_post_',$newComment->post->id,'#com-'.$newComment->id, 'newCom'));
                        }
                        
                        if ($taggedUsers) {
                            if ($taggedUsers[1]['name'] != 'noTags') {
                                foreach ($taggedUsersArray as $taggedUser) {
                                    $taggedUser->notify(new SystemNotification(__('nav.taggedInComment',[],$taggedUser->locale),'success','_user_home_post_',$post->id, '#com-'.$newComment->id, 'tagCom'));
                                }
                            }
                        }
    
                        $html = view('partials.home.ajax.ajaxWallComment')->withComments([$newComment])->render();
                    }else{
                        return response()->json(['status' => 'error', 'message' => 'You cannot see this post'], 400);
                    }
                }
            }
        }
        return response()->json(['status' => 'success','html' => $html], 200);
    }

    public function editComment(Request $request)
    {
        if ($request->ajax()) {

            $kek = $request->all();

            if ($request->commentType === "culture") {
                $request->validate([
                    'data.0.value' => ['string'],
                    'commentId'    => ['exists:culture_comments,id','nullable']
                ]);

                $comment = cultureComment::where('id',$request->commentId)->where('author_id',Auth::id())->first();
            }else{
                $request->validate([
                    'data.0.value' => ['string'],
                    'commentId'    => ['exists:comments,id','nullable']
                ]);

                $comment = Comment::where('id',$request->commentId)->where('author_id',Auth::id())->first();
            }

            $comment->message = $request->data[0]['value'];


            $taggedUsers = $request->data;
            $taggedUsersArray   = null;


            unset($taggedUsers[0]);
            
            
            if ($taggedUsers) {

                if ($taggedUsers[1]['name'] == 'noTags') {
                    $comment->tagged_users = null;
                }else{
                    $taggedUsers_json = array();
                    $taggedUsersArray = array();
                    foreach ($taggedUsers as $tagged) {
                        $user = User::find($tagged['value']);
                        $taggedUsers_json[] = $user->name;
                        $taggedUsersArray[] = $user;
                    }
                    $taggedUsers_json = json_encode($taggedUsers_json);
                    $comment->tagged_users = $taggedUsers_json;
                }
            }

            if ($comment->update()) {
                if ($request->commentType === "culture") {
                    $html = view('partials.culture.ajaxCommentSingle')->withComments([$comment])->render();
                    
                    if ($taggedUsers) {
                        if ($taggedUsers[1]['name'] != 'noTags') {
                            foreach ($taggedUsersArray as $taggedUser) {
                                $taggedUser->notify(new SystemNotification(__('nav.taggedInComment',[],$taggedUser->locale),'success','_culture_',$comment->item->name_slug, '#com-'.$comment->id, 'tagCultCom'));
                            }
                        }
                    }
                }else{
                    $html = view('partials.home.ajaxWallCommentSingle')->withComments([$comment])->render();
                    
                    if ($taggedUsers) {
                        if ($taggedUsers[1]['name'] != 'noTags') {
                            foreach ($taggedUsersArray as $taggedUser) {
                                $taggedUser->notify(new SystemNotification(__('nav.taggedInComment',[],$taggedUser->locale),'success','_user_home_post_',$post->id, '#com-'.$newComment->id, 'tagCom'));
                            }
                        }
                    }
                }
                
                return response()->json(['status' => 'success','html' => $html], 200);
            }
        }
    }

    public function deleteComment(Request $request)
    {
        if ($request->ajax()) {

            if ($request->commentType === "culture") {
                $request->validate([
                    'id'    => ['required','exists:culture_comments']
                ]);
                
                if(cultureComment::where('id',$request->id)->where('author_id',Auth::id())->delete()){
    
                    DB::table('likeable_like_counters')->where('likeable_type',"App\cultureComment")->where('likeable_id',$request->id)->delete();
                    DB::table('likeable_likes')->where('likeable_type',"App\cultureComment")->where('likeable_id',$request->id)->delete();
    
                    return response()->json(['status' => 'success'], 200);
                }
            }else{
                $request->validate([
                    'id'    => ['required','exists:comments']
                ]);
                
                if(Comment::where('id',$request->id)->where('author_id',Auth::id())->delete()){
    
                    DB::table('likeable_like_counters')->where('likeable_type',"App\Comment")->where('likeable_id',$request->id)->delete();
                    DB::table('likeable_likes')->where('likeable_type',"App\Comment")->where('likeable_id',$request->id)->delete();
    
                    return response()->json(['status' => 'success'], 200);
                }
            }
            return response()->json(['status' => 'error'], 400);
        }
    }

    public function likeComment(Request $request)
    {
        if($request->ajax()){

            if ($request->commentType === "culture") {
                $request->validate([
                    'commentId' => 'exists:culture_comments,id'
                ]);

                $comment = cultureComment::find($request->commentId);
    
                if ($comment->liked()) {
                    $comment->unlike();
                }else{
                    $comment->like();

                    if ($comment->author_id != Auth::id()) {
                        $comment->user->notify(new SystemNotification(__('nav.likeComNot',[],$comment->user->locale),'info','_culture_',$comment->item->name_slug,'#com-'.$comment->id, 'likeCultCom'));
                    }
                }
                return response()->json(['status' => 'success'], 200);
            }else{
                $request->validate([
                    'commentId' => 'exists:comments,id'
                ]);

                $comment = Comment::find($request->commentId);

                if ($comment->post->canBeSeen()) {
    
                    if ($comment->liked()) {
                        $comment->unlike();
                    }else{
                        $comment->like();
    
                        if ($comment->author_id != Auth::id()) {
                            $comment->user->notify(new SystemNotification(__('nav.likeComNot',[],$comment->user->locale),'info','_user_home_post_',$comment->post->id,'#com-'.$comment->id, 'likeCom'));
                        }
                    }
    
                    return response()->json(['status' => 'success'], 200);
                }else{
                    return response()->json(['status' => 'error', 'message' => 'You cannot see this post'], 400);
                }
            }
        }

    }

    public function getCultComments(Request $request, cultureItem $culture_item)
    {
        if ($request->ajax()) {

            $request->validate([
                'pagi'  => 'numeric'
            ]);

            $commentsAmount = count($culture_item->comments);

            $comments = cultureComment::where('item_id',$culture_item->id)->whereNull('parent_id')->take(5)->skip(5*$request->pagi)->orderBy('created_at','desc')->get();

            $html = view('partials.culture.ajaxItemComments')->withComments($comments)->withId($culture_item->id)->withPagi($request->pagi+1)->withCommentsAmount($commentsAmount - count($comments))->render();
            return response()->json(['status' => 'success', 'html' => $html], 200);
        }
    }

    public function getComments(Request $request, Post $post)
    {
        if ($request->ajax()) {

            $request->validate([
                'pagi'  => 'numeric'
            ]);

            $commentsAmount = count($post->comments);

            $comments = Comment::where('post_id',$post->id)->whereNull('parent_id')->take(5)->skip(5*$request->pagi)->orderBy('created_at','desc')->get();

            $html = view('partials.home.ajax.ajaxWallComment')->withComments($comments)->withId($post->id)->withPagi($request->pagi+1)->withCommentsAmount($commentsAmount - count($comments))->render();
            return response()->json(['status' => 'success', 'html' => $html], 200);
        }
    }

    public function getCultReplies(Request $request, cultureComment $culture_comment)
    {
        if ($request->ajax()) {

            $request->validate([
                'pagi'  => 'numeric'
            ]);

            $replies = cultureComment::where('parent_id',$culture_comment->id)->take(5)->skip(5*$request->pagi)->orderBy('created_at','desc')->get();

            $html = view('partials.culture.ajaxCommentReply')->withReplies($replies)->withId($culture_comment->id)->withPagi($request->pagi+1)->render();
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

            $html = view('partials..home.wallReplies')->withReplies($replies)->withId($comment->id)->withPagi($request->pagi+1)->render();
            return response()->json(['status' => 'success', 'html' => $html], 200);
        }
    }
    
    public function getCultTagged(Request $request, cultureComment $culture_comment)
    {
        if ($request->ajax()) {

            if($taggedUsers = json_decode($culture_comment->tagged_users)){
                $users = User::whereIn('name',$taggedUsers)->get();

                if (count($users) > 0) {
                    $taggedUsersHtml = view('partials.home.wallTaggedUsers')->withTaggedUsers($users)->render();
                }else{
                    return response()->json(['status' => 'error'], 400);
                }

                return response()->json(['status' => 'success', 'html' => $taggedUsersHtml], 200);
            }else{
                return response()->json(['status' => 'success', 'html' => ''], 200);
            }
        }
    }

    

    public function getTagged(Request $request, Comment $comment)
    {
        if ($request->ajax()) {

            if($taggedUsers = json_decode($comment->tagged_users)){
                $users = User::whereIn('name',$taggedUsers)->get();

                if (count($users) > 0) {
                    $taggedUsersHtml = view('partials.home.wallTaggedUsers')->withTaggedUsers($users)->render();
                }else{
                    return response()->json(['status' => 'error'], 400);
                }

                return response()->json(['status' => 'success', 'html' => $taggedUsersHtml], 200);
            }else{
                return response()->json(['status' => 'success', 'html' => ''], 200);
            }
        }
    }
}
