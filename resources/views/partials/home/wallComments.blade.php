@if($comments = $post->comments)
    @foreach($comments as $comment)
        <div id="com-{{$comment->id}}" class="comment row">
            <div class="col-2 commentProfilePicture">
                <a href="{{route('ProfileOtherView',['user' => $comment->user->name])}}">
                    <img class="profilePicture" src="{{asset('img/profile-pictures/'.$comment->user->picture)}}" alt="profile picture">
                </a>
            </div>
            <div class="col-10 commentContent">
                <div class="col-12 commentAuthor row">
                    <div class="col-7 commentAuthorName">
                        <a href="{{route('ProfileOtherView',['user' => $post->user->name])}}" style="color:unset">
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
                <i class="fas fa-fire likeCommentButton @if($comment->liked()){{"active"}}@endif" data-id="{{$comment->id}}" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.like')}}"></i>
                <span class="badge likesCount badge-pill badge-warning">@if($comment->likeCount != 0){{$comment->likeCount}}@endif</span>
                <i class="fas fa-reply replyButton" data-id="{{$comment->id}}" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.reply')}}"></i>
            </div>
        </div>

        <div class="commentRepliesBox container">

            @foreach($comment->replies as $reply)
            <div id="com-{{$reply->id}}" class="reply row">
                <div class="col-2 commentProfilePicture">
                    <a href="{{route('ProfileOtherView',['user' => $reply->user->name])}}">
                        <img class="profilePicture" src="{{asset('img/profile-pictures/'.$reply->user->picture)}}" alt="profile picture"> 
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
                                <i data-id="{{$reply->id}}" class="fas replyDelete commentDelete fa-times" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.deleteComment')}}"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-12 commentDate">{{$reply->created_at->diffForHumans()}}</div>
                    <div class="col-12 commentDesc">
                        @php
                            $regex = '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#';
                            preg_match_all($regex, e($reply->message), $commentLinks);
                            if (isset($commentLinks)) {
                                $embedLinks = array();
                                foreach ($commentLinks[0] as $link) {
                                    $html = "<a href='".$link."' target='__blank'>".$link."</a>";
                                    $embedLinks[] = $html;
                                }
                                echo nl2br(preg_replace_array($regex, $embedLinks, e($reply->message)));
                            }
                        @endphp
                    </div>
                    <div class="col-12 commentTags row">
                        @if ($taggedUsers = json_decode($reply->tagged_users))
                            @foreach ($taggedUsers as $tag)
                                <a href="{{route('ProfileOtherView',['user' => $tag])}}" class="col-4 commentTaggedUser" target="__blank">
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
        </div>
    @endforeach
@endif