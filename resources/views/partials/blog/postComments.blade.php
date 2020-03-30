@foreach($post->comments as $comment)
    <div id="com-{{$comment->id}}" class="comment row">
        <div class="col-2 commentProfilePicture">
            <a href="{{route('ProfileOtherView',['user' => $comment->user->name])}}">
                <img class="profilePicture" src="{{asset('img/profile-pictures/'.$comment->user->picture)}}" alt="Profile Picture">
            </a>
        </div>
        <div class="col-10 commentContent">
            <div class="col-12 commentAuthor row">
                <div class="col-7 commentAuthorName">
                    <a href="{{route('ProfileOtherView',['user' => $comment->user->name])}}" style="color:unset">
                        {{$comment->user->name}}
                    </a>
                </div>
                @auth
                    @if(auth()->user()->id == $comment->user->id)
                        <div class="col-5 commentAuthorButtons">
                            <i data-id="{{$comment->id}}" class="fas commentEdit fa-edit" data-toggle="modal" data-target="#commentEditModal" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.editComment')}}"></i>
                            <i data-id="{{$comment->id}}" class="fas commentDelete fa-times" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.deleteComment')}}"></i>
                        </div>
                    @endif
                @endauth
            </div>
            <div class="col-12 commentDate">{{$comment->created_at->diffForHumans()}}</div>
            <div class="col-12 commentDesc">
                @php
                    $regex = '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#';
                    preg_match_all($regex, e($comment->message), $commentLinks);
                    if (isset($commentLinks)) {
                        $embedLinks = array();
                        foreach ($commentLinks[0] as $link) {
                            $html = "<a href='".$link."' target='__blank'>".$link."</a>";
                            $embedLinks[] = $html;
                        }
                        echo nl2br(preg_replace_array($regex, $embedLinks, e($comment->message)));
                    }
                @endphp
            </div>
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
            <i class="fas fa-fire @if(auth()->check()) likeCommentButton @endif @if($comment->liked()){{"active"}}@endif" data-id="{{$comment->id}}" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.like')}}"></i>
            <span class="badge likesCount badge-pill badge-warning">
                @if($comment->likeCount != 0){{$comment->likeCount}}@endif
            </span>
            @if(auth()->check())
                <i class="fas fa-reply replyButton" data-id="{{$comment->id}}" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.reply')}}"></i>
            @endif
        </div>
    </div>

    <div class="commentRepliesBox container">

        @if(count($comment->replies) > 0)
        <div id="com-{{$comment->replies[0]->id}}" class="reply row">
            <div class="col-2 commentProfilePicture">
                <a href="{{route('ProfileOtherView',['user' => $comment->replies[0]->user->name])}}">
                    <img class="profilePicture" src="{{asset('img/profile-pictures/'.$comment->replies[0]->user->picture)}}" alt="Profile Picture">
                </a>
            </div>
            <div class="col-9 commentContent">
                <div class="col-12 commentAuthor row">
                    <div class="col-7 commentAuthorName">
                        <a href="{{route('ProfileOtherView',['user' => $comment->replies[0]->user->name])}}" style="color:unset">
                                {{$comment->replies[0]->user->name}}
                        </a>
                    </div>
                    @auth
                        @if(auth()->user()->id == $comment->replies[0]->user->id)
                            <div class="col-5 commentAuthorButtons">
                                <i data-id="{{$comment->replies[0]->id}}" class="fas commentEdit fa-edit" data-toggle="modal" data-target="#commentEditModal" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.editComment')}}"></i>
                                <i data-id="{{$comment->replies[0]->id}}" class="fas replyDelete commentDelete fa-times" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.deleteComment')}}"></i>
                            </div>
                        @endif
                    @endauth
                </div>
                <div class="col-12 commentDate">{{$comment->replies[0]->created_at->diffForHumans()}}</div>
                <div class="col-12 commentDesc">
                    @php
                        $regex = '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#';
                        preg_match_all($regex, e($comment->replies[0]->message), $commentLinks);
                        if (isset($commentLinks)) {
                            $embedLinks = array();
                            foreach ($commentLinks[0] as $link) {
                                $html = "<a href='".$link."' target='__blank'>".$link."</a>";
                                $embedLinks[] = $html;
                            }
                            echo nl2br(preg_replace_array($regex, $embedLinks, e($comment->replies[0]->message)));
                        }
                    @endphp
                </div>
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
                <i class="fas fa-fire @if(auth()->check()) likeCommentButton @endif @if($comment->replies[0]->liked()){{"active"}}@endif" data-id="{{$comment->replies[0]->id}}" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.like')}}"></i>
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


@if (count($post->comments) == 5)
<button type="button" data-id="{{$post->name_slug}}" data-pagi="@if(!isset($pagi)){{"1"}}@else {{$pagi}} @endif" class="btn commentsMoreBtn">
    {{__('activityWall.moreComments')}}
    <i class="ml-1 fas fa-sort-down"></i>
</button>
@endif