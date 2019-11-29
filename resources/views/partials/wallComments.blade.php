@if($comments = $post->comments)
    @foreach($comments as $comment)
        <div id="com-{{$comment->id}}" class="comment row">
            <div class="col-2 commentProfilePicture">
                <img class="profilePicture" src="{{asset('img/profile-pictures/'.$comment->user->picture)}}">
            </div>
            <div class="col-10 commentContent">
                <div class="col-12 commentAuthor row">
                    <div class="col-10 commentAuthorName">{{$comment->user->name}}</div>
                    @if(auth()->user()->id == $comment->user->id)
                        <div class="col-2 commentAuthorButtons">
                            <i data-id="{{$comment->id}}" class="fas commentEdit fa-edit" data-toggle="modal" data-target="#commentEditModal"></i>
                            <i data-id="{{$comment->id}}" class="fas commentDelete fa-times"></i>
                        </div>
                    @endif
                </div>
                <div class="col-12 commentDate">{{$comment->created_at->diffForHumans()}}</div>
                <div class="col-12 commentDesc">{{$comment->message}}</div>
            </div>
            <div class="col-12 commentUserButtons">
                <i class="fas fa-fire likeCommentButton @if($comment->liked()){{"active"}}@endif" data-id="{{$comment->id}}"></i><span class="badge likesCount badge-pill badge-warning">@if($comment->likeCount != 0){{$comment->likeCount}}@endif</span>
                <i class="fas fa-reply replyButton" data-id="{{$comment->id}}"></i>
            </div>
        </div>

        <div class="commentRepliesBox container">

            @foreach($comment->replies as $reply)
            <div id="com-{{$reply->id}}" class="reply row">
                <div class="col-2 commentProfilePicture">
                    <img class="profilePicture" src="{{asset('img/profile-pictures/'.$reply->user->picture)}}">
                </div>
                <div class="col-10 commentContent">
                    <div class="col-12 commentAuthor row">
                        <div class="col-9 commentAuthorName">{{$reply->user->name}}</div>
                        @if(auth()->user()->id == $reply->user->id)
                            <div class="col-3 commentAuthorButtons">
                                <i data-id="{{$reply->id}}" class="fas commentEdit fa-edit" data-toggle="modal" data-target="#commentEditModal"></i>
                                <i data-id="{{$reply->id}}" class="fas replyDelete commentDelete fa-times"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-12 commentDate">{{$reply->created_at->diffForHumans()}}</div>
                    <div class="col-12 commentDesc">{{$reply->message}}</div>
                </div>
                <div class="col-12 commentUserButtons">
                    <i class="fas fa-fire likeCommentButton @if($reply->liked()){{"active"}}@endif" data-id="{{$reply->id}}"></i><span class="badge likesCount badge-pill badge-warning">@if($reply->likeCount != 0){{$reply->likeCount}}@endif</span>
                </div>
            </div>
            @endforeach
        </div>
    @endforeach
@endif