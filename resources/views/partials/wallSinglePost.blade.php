<div class="postBox container">
    <article id="post{{$post->id}}" class="post">
        <header class="postAuthor row">
            <div class="col-1">
                <a href="{{route('ProfileOtherView',['user' => $post->user->name])}}">
                    <img class="postAuthorPicture" src="{{asset('img/profile-pictures/'.$post->user->picture)}}">
                </a>
            </div>
            <div class="col-6 postAuthorName">
                <a class="postAuthorLink" href="{{route('ProfileOtherView',['user' => $post->user->name])}}">
                    {{$post->user->name}}@if($post->user->is_admin)<small class="text-muted adminStatus">{{__('activityWall.adminStatus')}}</small>@endif
                </a>
            </div>
            @if ($post->user_id == auth()->user()->id)
                <div class="col-4 postAuthorButtons">
                    <i class="fas postEdit fa-edit" data-id="{{$post->id}}" data-toggle="modal" data-target="#editModal"></i>
                    <i class="fas postDelete fa-times" data-id="{{$post->id}}"></i>
                </div>
            @endif
        </header>
        <main class="postDesc row">
            <div class="postPhotos col-12">
                @if ($pictures = json_decode($post->pictures))
                    @foreach ($pictures as $picture)
                        <a href="{{asset('img/post-pictures/'.$picture)}}" data-lightbox="post{{$post->id}}-Pictures">
                            <img class="postPicture" src="{{asset('img/post-pictures/'.$picture)}}">
                        </a>
                    @endforeach
                @endif
            </div>
            <div class="postContent col-12">{{$post->desc}}</div>
            <div class="postTags col-12"></div>
        </main>
        <footer class="postFooter row">
            <button class="col-5 btn btn-block likePostButton @if($post->liked()){{"active"}}@endif" data-id="{{$post->id}}"><i class="fas fa-fire"></i><span class="badge likesCount badge-pill badge-warning">@if($post->likeCount != 0){{$post->likeCount}}@endif</span> {{__('activityWall.like')}}</button>
            <button class="col-5 btn btnComment btn-block" data-id="{{$post->id}}" data-pagi="0">
                <i class="far fa-comments"></i><span class="badge postCommentsCount badge-pill badge-warning">@if(count($post->comments) > 0){{count($post->comments)}}@endif</span>
                    {{__('activityWall.comment')}}
            </button>
        </footer>
    </article>

    <div class="postComments mt-4">
        <form class="commentsForm" data-id="{{$post->id}}" method="post">
            <div class="input-group row">
                <input type="text" name="commentDesc" class="form-control commentsDesc col-11" placeholder="Napisz Komentarz" aria-label="Napisz Komentarz">
                <div class="input-group-append col-1 commentButtons">
                    <i class="fas fa-user-tag"></i>
                </div>
            </div>
        </form>
        <output class="commentsFeed"  id="feed-{{$post->id}}">
            @include('partials.wallComments')
        </output>
    </div>
    <hr>
</div>