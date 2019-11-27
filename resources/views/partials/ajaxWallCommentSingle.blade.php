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
            <i class="fas fa-fire likeCommentButton @if($comment->liked()){{"active"}}@endif" data-id="{{$comment->id}}"></i>
            <i class="fas fa-reply replyButton" data-id="{{$comment->id}}"></i>
        </div>
    </div>
@endforeach