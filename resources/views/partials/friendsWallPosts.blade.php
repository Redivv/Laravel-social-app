@foreach ($posts as $post)
    <article id="post{{$post->id}}" class="post container">
        <header class="postAuthor row">
            <div class="col-1">
                <img class="postAuthorPicture" src="{{asset('img/profile-pictures/'.$post->user->picture)}}">
            </div>
            <div class="col-9 postAuthorName">{{$post->user->name}}</div>
            <div class="col-2 postAuthorButtons"><i class="fas fa-edit"></i> <i class="fas fa-times"></i></div>
        </header>
        <main class="postDesc row">
            <div class="postPhotos col-12">
                @if ($pictures = json_decode($post->pictures))
                    @foreach ($pictures as $picture)
                        <img class="postPicture" src="{{asset('img/post-pictures/'.$picture)}}">
                    @endforeach
                @endif
            </div>
            <div class="postContent col-12">{{$post->desc}}</div>
            <div class="postTags col-12"></div>
        </main>
        <footer class="postFooter row">
            <button class="col-5 btn btn-block"><i class="fas fa-fire"></i> Poleć</button>
            <button class="col-5 btn btn-block"><i class="far fa-comments"></i> Skomentuj</button>
        </footer>
    </article>
    <hr>
@endforeach