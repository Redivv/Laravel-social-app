@foreach ($replies as $reply)
    <div id="com-{{$reply->id}}" class="reply row">
        <div class="col-2 commentProfilePicture">
            <a href="{{route('ProfileOtherView',['user' => $reply->user->name])}}">
                <img class="profilePicture" src="{{asset('img/profile-pictures/'.$reply->user->picture)}}">
            </a>
        </div>
        <div class="col-10 commentContent">
            <div class="col-12 commentAuthor row">
                <div class="col-7 commentAuthorName">
                    <a href="{{route('ProfileOtherView',['user' => $reply->user->name])}}" style="color:unset">
                        {{$reply->user->name}}
                    </a>
                </div>
                @if(auth()->user()->id == $reply->user->id)
                    <div class="col-5 commentAuthorButtons">
                        <i data-id="{{$reply->id}}" class="fas commentEdit fa-edit" data-toggle="modal" data-target="#commentEditModal" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.editComment')}}"></i>
                        <i data-id="{{$reply->id}}" class="fas commentDelete replyDelete fa-times" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.deleteComment')}}"></i>
                    </div>
                @endif
            </div>
            <div class="col-12 commentDate">{{$reply->created_at->diffForHumans()}}</div>
            <div class="col-12 commentDesc">{{$reply->message}}</div>
            <div class="col-12 commentTags row">
                @if ($taggedUsers = json_decode($reply->tagged_users))
                    @foreach ($taggedUsers as $tag)
                        <a href="{{route('ProfileOtherView',['user' => $tag])}}" class="col-3 commentTaggedUser" target="__blank">
                            <span class="taggedUserLabel">{{$tag}}</span>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="col-12 commentUserButtons">
            <i class="fas fa-fire likeCommentButton @if($reply->liked()){{"active"}}@endif" data-id="{{$reply->id}}" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.like')}}"></i>
            <span class="badge likesCount badge-pill badge-warning">
                @if($reply->likeCount != 0){{$reply->likeCount}}@endif
            </span>
        </div>
    </div>
    
@endforeach

@if (count($replies) == 5)
<button type="button" data-id="{{$id}}" data-pagi="{{$pagi}}" class="btn repliesMoreBtn">
    {{__('activityWall.moreReplies')}}
    <i class="ml-1 fas fa-sort-down"></i>
</button>
@endif