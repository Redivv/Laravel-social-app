@foreach ($replies as $comment)
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
                            <i data-id="{{$comment->id}}" class="fas replyDelete commentDelete fa-times" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.deleteComment')}}"></i>
                        </div>
                    @endif
                @endauth
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
            <i class="fas fa-fire @if(auth()->check()) likeCommentButton @endif @if($comment->liked()){{"active"}}@endif" data-id="{{$comment->id}}"></i>
            <span class="badge likesCount badge-pill badge-warning" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.like')}}">
                @if($comment->likeCount != 0){{$comment->likeCount}}@endif
            </span>
        </div>
    </div>
@endforeach