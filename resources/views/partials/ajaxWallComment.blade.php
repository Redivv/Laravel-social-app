@foreach($comments as $comment)
    <div id="com-{{$comment->id}}" class="comment row">
        <div class="col-2 commentProfilePicture">
            <a href="{{route('ProfileOtherView',['user' => $comment->user->name])}}">
                <img class="profilePicture" src="{{asset('img/profile-pictures/'.$comment->user->picture)}}">
            </a>
        </div>
        <div class="col-10 commentContent">
            <div class="col-12 commentAuthor row">
                <div class="col-7 commentAuthorName">
                    <a href="{{route('ProfileOtherView',['user' => $comment->user->name])}}" style="color:unset">
                        {{$comment->user->name}}
                    </a>
                </div>
                @if(auth()->user()->id == $comment->user->id)
                    <div class="col-5 commentAuthorButtons">
                        <i data-id="{{$comment->id}}" class="fas commentEdit fa-edit" data-toggle="modal" data-target="#commentEditModal" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.editComment')}}"></i>
                        <i data-id="{{$comment->id}}" class="fas commentDelete fa-times" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.deleteComment')}}"></i>
                    </div>
                @endif
            </div>
            <div class="col-12 commentDate">{{$comment->created_at->diffForHumans()}}</div>
            <div class="col-12 commentDesc">{{$comment->message}}</div>
            <div class="col-12 commentTags row">
                @if ($taggedUsers = json_decode($comment->tagged_users))
                    @foreach ($taggedUsers as $tag)
                        <a href="{{route('ProfileOtherView',['user' => $tag])}}" class="col-4 commentTaggedUser" target="__blank">
                            <span class="taggedUserLabel">{{$tag}}</span>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="col-12 commentUserButtons">
            <i class="fas fa-fire likeCommentButton @if($comment->liked()){{"active"}}@endif" data-id="{{$comment->id}}" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.like')}}"></i>
            <span class="badge likesCount badge-pill badge-warning">
                @if($comment->likeCount != 0){{$comment->likeCount}}@endif
            </span>
            <i class="fas fa-reply replyButton" data-id="{{$comment->id}}" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.reply')}}"></i>
        </div>
    </div>

    <div class="commentRepliesBox container">

        @if(count($comment->replies) > 0)
        <div id="com-{{$comment->replies[0]->id}}" class="reply row">
            <div class="col-2 commentProfilePicture">
                <a href="{{route('ProfileOtherView',['user' => $comment->replies[0]->user->name])}}">
                    <img class="profilePicture" src="{{asset('img/profile-pictures/'.$comment->replies[0]->user->picture)}}">
                </a>
            </div>
            <div class="col-9 commentContent">
                <div class="col-12 commentAuthor row">
                    <div class="col-7 commentAuthorName">
                        <a href="{{route('ProfileOtherView',['user' => $comment->replies[0]->user->name])}}" style="color:unset">
                                {{$comment->replies[0]->user->name}}
                        </a>
                    </div>
                    @if(auth()->user()->id == $comment->replies[0]->user->id)
                        <div class="col-5 commentAuthorButtons">
                            <i data-id="{{$comment->replies[0]->id}}" class="fas commentEdit fa-edit" data-toggle="modal" data-target="#commentEditModal" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.editComment')}}"></i>
                            <i data-id="{{$comment->replies[0]->id}}" class="fas replyDelete commentDelete fa-times" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.deleteComment')}}"></i>
                        </div>
                    @endif
                </div>
                <div class="col-12 commentDate">{{$comment->replies[0]->created_at->diffForHumans()}}</div>
                <div class="col-12 commentDesc">{{$comment->replies[0]->message}}</div>
                <div class="col-12 commentTags row">
                    @if ($taggedUsers = json_decode($comment->replies[0]->tagged_users))
                        @foreach ($taggedUsers as $tag)
                            <a href="{{route('ProfileOtherView',['user' => $tag])}}" class="col-4 commentTaggedUser" target="__blank">
                                <span class="taggedUserLabel">{{$tag}}</span>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="col-12 commentUserButtons">
                <i class="fas fa-fire likeCommentButton @if($comment->replies[0]->liked()){{"active"}}@endif" data-id="{{$comment->replies[0]->id}}" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.like')}}"></i>
                <span class="badge likesCount badge-pill badge-warning">
                    @if($comment->replies[0]->likeCount != 0){{$comment->replies[0]->likeCount}}@endif
                </span>
            </div>
        </div>
        @endif

        @if(count($comment->replies) > 1)
            <button type="button" data-id="{{$comment->id}}" data-pagi="0" class="btn repliesMoreBtn">
                {{__('activityWall.moreReplies')}}
                <i class="ml-1 fas fa-sort-down"></i><span class="badge repliesCount badge-pill badge-warning">{{count($comment->replies) - 1}}</span>
            </button>
        @endif
    </div>
@endforeach


@if (count($comments) == 5)
<button type="button" data-id="{{$id}}" data-pagi="{{$pagi}}" class="btn commentsMoreBtn">
    {{__('activityWall.moreComments')}}
    <i class="ml-1 fas fa-sort-down"></i><span class="badge commentsMoreCount badge-pill badge-warning">{{$commentsAmount}}</span>
</button>
@endif