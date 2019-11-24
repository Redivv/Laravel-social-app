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
            <i class="fas fa-fire"></i>
            <i class="fas fa-reply replyButton" data-id="{{$comment->id}}"></i>
        </div>
    </div>

    <div class="commentRepliesBox container">

        @if(count($comment->replies) > 0)
        <div id="com-{{$comment->replies[0]->id}}" class="reply row">
            <div class="col-2 commentProfilePicture">
                <img class="profilePicture" src="{{asset('img/profile-pictures/'.$comment->replies[0]->user->picture)}}">
            </div>
            <div class="col-10 commentContent">
                <div class="col-12 commentAuthor row">
                    <div class="col-9 commentAuthorName">{{$comment->replies[0]->user->name}}</div>
                    @if(auth()->user()->id == $comment->replies[0]->user->id)
                        <div class="col-3 commentAuthorButtons">
                            <i data-id="{{$comment->replies[0]->id}}" class="fas commentEdit fa-edit" data-toggle="modal" data-target="#commentEditModal"></i>
                            <i data-id="{{$comment->replies[0]->id}}" class="fas commentDelete fa-times"></i>
                        </div>
                    @endif
                </div>
                <div class="col-12 commentDate">{{$comment->replies[0]->created_at->diffForHumans()}}</div>
                <div class="col-12 commentDesc">{{$comment->replies[0]->message}}</div>
            </div>
            <div class="col-12 commentUserButtons">
                <i class="fas fa-fire"></i>
            </div>
        </div>
        @endif

        @if(count($comment->replies) > 1)
            <button type="button" data-id="{{$comment->id}}" class="btn repliesMoreBtn">
                Pokaż Więcej Odpowiedzi
                <i class="ml-1 fas fa-sort-down"></i><span class="badge repliesCount badge-pill badge-warning">{{count($comment->replies) - 1}}</span>
            </button>
        @endif
    </div>
@endforeach