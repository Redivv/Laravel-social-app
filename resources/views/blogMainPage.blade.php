@extends('layouts.app')

@section('titleTag')
    <title>
        Safo | {{__('app.blog')}}
    </title>
@endsection

@section('content')
<div class="spinnerOverlay d-none">
    <div class="spinner-border text-warning" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>
<div class="darkOverlay d-none"></div>

<div class="container-fluid">
    <div class="row">
        <main class="col-9 blogFeed">
            <header class="blogFeed-sortBtns">
                <a class="sortBtn active" href="#">{{__('blog.dateAdded')}}</a>
                <a class="sortBtn" href="#">{{__('blog.likes')}}</a>
                <a class="sortBtnDir" href="#" data-tool="tooltip" title="{{__('blog.sortAsc')}}" data-placement="bottom"><i class="fas fa-sort-amount-up-alt"></i></a>
                <a class="sortBtnDir" href="#" data-tool="tooltip" title="{{__('blog.sortDesc')}}" data-placement="bottom"><i class="fas fa-sort-amount-down-alt"></i></a>
            </header>
            <output class="blogFeed-posts">
                @if (count($posts) > 0)
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
                                    {!!Str::words($post->description,20,'...')!!}
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
                                <button class="btn likePostButton @auth likeBtn @endauth" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.like')}}">
                                    <i class="fas fa-fire likeIcon"></i>
                                    <span class="likesAmount">5</span>
                                </button>
                            </footer>
                            <hr>
                        </article>
                    @endforeach
                @else
                    <h2>
                        {{__('blog.noPosts')}}
                    </h2>
                @endif
            </output>
        </main>
        <aside class="col-3 blogExtraPanes">

            <div id="searchWidget" class="blogWidget">
                <header class="widgetHeader">
                    <span>{{__('blog.searchTags')}}</span>
                </header>
                <main class="widgetContent">
                    <form id="searchWidget-form">
                        <div class="tagCriteria input-group">
                            <input id="tagName" type="text" class="form-control">
                            <button class="btn addTagButton" type="button">{{__('searcher.add')}}</button>
                        </div>
                        <output class="row" id="searchTags"></output>
                    </form>
                </main>
                <footer class="widgetFooter">
                    <button class="btn widgetBtn" type="submit">{{__('searcher.search')}}</button>
                </footer>
            </div>

            @if (count($cats) > 0)
                <div id="categoriesWidget" class="blogWidget">
                    <header class="widgetHeader">
                        <span>{{__('blog.categories')}}</span>
                    </header>
                    <main class="widgetContent row">
                        @foreach ($cats as $cat)
                            <span class="col widgetCategory">
                                <a href="#">{{$cat->name}}</a>
                            </span>
                        @endforeach
                    </main>
                </div>
            @endif

            <div id="calendar">
                Kalendarz
            </div>
        </aside>
        <span id="showSearchMenu"><i class="fas fa-search"></i></span>
    </div>
</div>

@endsection

@push('styles')
    <style>
        .navBlog > .nav-link{
            color: #f66103 !important;
        }
    </style>
    <link rel="stylesheet" href="{{asset("jqueryUi\jquery-ui.min.css")}}">
@endpush

@push('scripts')
<script>
    var baseUrl             = "{{url('/')}}";
    var deleteHobby         =  "{{__('activityWall.deleteTags')}}";
    var confirmMsg          =  "{{__('admin.confirmMsg')}}";
</script>

<script src="{{asset("jqueryUi\jquery-ui.min.js")}}"></script>

<script src="{{asset('js/blog.js')}}"></script>

<script src="{{asset('js/emoji.js')}}"></script>

<script>
    
    $( "#tagName" ).autocomplete({
 
        source: function(request, response) {
            $.ajax({
                url: baseUrl+"/ajax/tag/autocompleteHobby",
                data: {
                    term : request.term
                },
                dataType: "json",
                success: function(data){
                    var resp = $.map(data,function(obj){
                    return obj.name;
                }); 
                response(resp);
                }
            });
        },
        minLength: 1
    });
</script>

<script defer>
    Echo.join('online')
        .joining((user) => {
            axios.patch('/api/user/'+ user.name +'/online', {
                    api_token : user.api_token
            });
        })

        .leaving((user) => {
            axios.patch('/api/user/'+ user.name +'/offline', {
                api_token : user.api_token
            });
        })
</script>
@endpush

