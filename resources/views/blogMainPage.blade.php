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
    <div id="blogContainer" class="row">
        <main class="blogFeed">
            <header class="blogFeed-sortBtns">
                <form id="sortForm" class="d-inline" method="GET" action="{{route('blog.mainPage')}}">
                    @if (request('sortCrit') == "likes")
                        <select class="form-control" id="sortRange" name="sortRange">
                            <option value="week">kek</option>
                            <option value="month">kew</option>
                            <option value="all">kurw</option>
                        </select>
                    @endif
                    <div class="btn-group-toggle d-inline" data-toggle="buttons">
                        <label class="btn sortBtn @if(request('sortCrit') != 'likes') active @endif">
                          <input type="radio" name="sortCrit" value="date" autocomplete="off" @if(request('sortCrit') != 'likes') checked @endif> {{__('blog.dateAdded')}}
                        </label>
                        <label class="btn sortBtn @if(request('sortCrit') == 'likes') active @endif">
                          <input type="radio" name="sortCrit" value="likes" autocomplete="off" @if(request('sortCrit') == 'likes') checked @endif"> {{__('blog.likes')}}
                        </label>
                      </div>

                      @if (request('postTags'))
                        @foreach (request('postTags') as $tag)
                            <input type="hidden" name="postTags[]" value="{{$tag}}">
                        @endforeach
                      @elseif(request('postCategory'))
                        <input type="hidden" name="postCategory" value="{{request('postCategory')}}">
                      @endif

                      <div class="btn-group-toggle d-inline" data-toggle="buttons">
                        <label class="btn sortBtnDir @if(request('sortDir') == 'asc') active @endif">
                          <input type="radio" name="sortDir" value="asc" autocomplete="off" @if(request('sortDir') == 'asc') checked @endif data-tool="tooltip" title="{{__('blog.sortAsc')}}" data-placement="bottom"><i class="fas fa-sort-amount-up-alt"></i>
                        </label>
                        <label class="btn sortBtnDir @if(request('sortDir') != 'asc') active @endif">
                          <input type="radio" name="sortDir" value="desc" autocomplete="off" @if(request('sortDir') != 'asc') checked @endif data-tool="tooltip" title="{{__('blog.sortDesc')}}" data-placement="bottom"><i class="fas fa-sort-amount-down-alt"></i>
                        </label>
                      </div>
                </form>
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
                                    {!!Str::words($post->description,50,'...')!!}
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
        <aside class="blogExtraPanes">

            <div id="searchWidget" class="blogWidget">
                <header class="widgetHeader">
                    <span>{{__('blog.searchTags')}}</span>
                </header>
                <main class="widgetContent">
                    <form id="searchWidget-form" method="GET" action="{{route('blog.mainPage')}}">
                        <div class="tagCriteria input-group">
                            <input id="tagName" type="text" class="form-control">
                            <button class="btn addTagButton" type="button">{{__('searcher.add')}}</button>
                        </div>
                        <output class="row" id="searchTags">
                            @if (request('postTags'))
                                @foreach (request('postTags') as $tag)
                                    <div class="col postTag" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.deleteTags')}}">
                                        <span>{{$tag}}</span>
                                        <input type="hidden" name="postTags[]" value="{{$tag}}">
                                    </div>
                                @endforeach
                            @endif
                        </output>
                    </form>
                </main>
                <footer class="widgetFooter">
                    <button class="btn widgetBtn" form="searchWidget-form" type="submit">{{__('searcher.search')}}</button>
                </footer>
            </div>

            @if (count($cats) > 0)
                <div id="categoriesWidget" class="blogWidget">
                    <header class="widgetHeader">
                        <span>{{__('blog.categories')}}</span>
                    </header>
                    <main class="widgetContent">
                        <form class="row" action="{{route('blog.mainPage')}}" method="GET">
                            @foreach ($cats as $cat)
                                <button class="col widgetCategory btn btn-link" type="submit" name="postCategory" value="{{$cat->id}}">{{$cat->name}}</button>
                            @endforeach
                        </form>
                    </main>
                </div>
            @endif

            <div id="eventsWidget" class="blogWidget">
                <header class="widgetHeader">
                    <span>{{__('blog.calendar')}}</span>
                </header>
                <main class="widgetContent">
                    <div id="calendar"></div>
                    <div id="eventsList" class="d-none">
                        <button class="btn" id="calendarButton">
                            {{__('blog.eventCalendar')}}
                        </button>
                        @if (count($eventsList) > 0)
                            <ul>
                                @foreach ($eventsList as $event)
                                    <a href="{{$event->url}}">
                                        <li class="event row">
                                            <span class="eventTime col-12">{{str_replace('-','.',substr($event->starts_at,0,-3))}} -- {{str_replace('-','.',substr($event->ends_at,0,-3))}}</span>
                                            <span class="eventTitle col-12">{{$event->name}}</span>
                                        </li>
                                    </a>
                                @endforeach
                            </ul>
                        @else
                            <div class="noEventsText">{{__('blog.noEvents')}}</div>
                        @endif
                    </div>
                </main>
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
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
@endpush

@push('scripts')
<script>
    var baseUrl             = "{{url('/')}}";
    var deleteHobby         =  "{{__('activityWall.deleteTags')}}";
    var confirmMsg          =  "{{__('admin.confirmMsg')}}";
    var events = '{!!$events!!}';
    var listText = "{{__('blog.eventsList')}}";
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

