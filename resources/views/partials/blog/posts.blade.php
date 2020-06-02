
@foreach ($posts as $post)
    <article class="blogFeed-post">
        @auth
            @if (auth()->user()->isAdmin())
                <div class="col-12 adminButtons">
                    <a href="{{route('adminBlog')."?elementType=blogPost&elementId=".$post->id}}" data-tool="tooltip" title="{{__('admin.edit')}}" data-placement="bottom">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form method="post" action="#" class="deletePost">
                        @method('delete')
                        <input type="hidden" name="elementId" value="{{$post->id}}">
                        <button class="btn" type="submit" data-tool="tooltip" title="{{__('admin.delete')}}" data-placement="bottom">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>
                </div>
            @endif
        @endauth
        <header class="postHeader row">
            <div class="postTitle col-12">
                <a href="{{route('blog.post',['blogPost' => $post->name_slug])}}">{{$post->name}}</a>
            </div>
            <div class="postDetails col-12">
                <span class="postAuthor"><a href="{{route('ProfileOtherView',['user' => $post->user->name])}}" target="__blank">{{$post->user->name}}</a></span>
                <span class="postDate">{{$post->created_at->diffForHumans()}}</span>
            </div>
        </header>
        <main class="postContent">
            @if ($thumb = json_decode($post->thumbnail)[0])
                <a href="{{route('blog.post',['blogPost' => $post->name_slug])}}">
                    <img class="postThumbnail" src="{{asset('img/blog-pictures/'.$thumb)}}" alt="Post Thumbnail">
                </a>
            @endif
            <main class="postDesc">
                {!!Str::words(strip_tags($post->description),50,'...')!!}
            </main>
        </main>
        <footer class="postFooter">
            <output class="postTags row">
                @if (count($post->tagNames()) > 0)
                    @foreach ($post->tagNames() as $tag)
                        <a href="#" class="postTag col" data-tool="tooltip" title="{{__('culture.searchTag')}}" data-placement="bottom">{{$tag}}</a>
                    @endforeach
                @endif
            </output>
            <a href="{{route('blog.post',['blogPost' => $post->name_slug])}}" class="postReadMoreBtn">{{__('blog.readMore')}} <i class="fas fa-chevron-right"></i></a>
            <button class="btn likePostButton @auth likeBtn @endauth @if($post->likeCount > 0) active @endif" data-id="{{$post->id}}" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.like')}}">
                <i class="fas fa-fire likeIcon"></i>
                <span class="likesAmount @if($post->likeCount == 0) d-none @endif">{{$post->likeCount}}</span>
            </button>
        </footer>
        @if (($loop->index + 3) % 4 === 0 )
            <!-- Blog Ads -->
            <ins class="adsbygoogle"
                style="display:block"
                data-ad-client="ca-pub-2738699172205892"
                data-ad-slot="5560623993"
                data-ad-format="auto"
                data-full-width-responsive="true"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        @endif
        <hr>
    </article>
@endforeach