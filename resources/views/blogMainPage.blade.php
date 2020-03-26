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
            </header>
            <output class="blogFeed-posts">
                <article class="blogFeed-post">
                    <header class="postHeader row">
                        <div class="postTitle col-12">
                            <a href="#">Tutuł Posta</a>
                        </div>
                        <div class="postDetails col-12">
                            <span class="postAuthor"><a href="#">To ja Byłem</a></span>
                            <span class="postDate">2 dni temu</span>
                        </div>
                    </header>
                    <main class="postContent">
                        <a href="#">
                            <img class="postThumbnail" src="https://via.placeholder.com/728x90.png?text=Visit   +WhoIsHostingThis.com+Buyers+Guide" alt="Post Thumbnail">
                        </a>
                        <p class="postDesc">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam aliquet tempor augue, in tristique diam feugiat et. Morbi placerat, velit sit amet blandit euismod, leo nisl porttitor tortor, ut fringilla nibh leo ac purus. Phasellus ipsum sapien, dignissim vitae dui nec, convallis iaculis urna. Vivamus congue eget felis tincidunt feugiat...
                        </p>
                    </main>
                    <footer class="postFooter">
                        <output class="postTags row">
                            <a href="#" class="postTag col" data-tool="tooltip" title="{{__('culture.searchTag')}}" data-placement="bottom">Penisy</a>
                        </output>
                        <a href="#" class="postReadMoreBtn">{{__('blog.readMore')}} <i class="fas fa-chevron-right"></i></a>
                        <button class="btn likePostButton" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.like')}}">
                            <i class="fas fa-fire likeIcon"></i>
                            <span class="likesAmount">5</span>
                        </button>
                    </footer>
                </article>
                <hr>
                <article class="blogFeed-post">
                    <header class="postHeader row">
                        <div class="postTitle col-12">
                            <a href="#">Tutuł Posta</a>
                        </div>
                        <div class="postDetails col-12">
                            <span class="postAuthor"><a href="#">To ja Byłem</a></span>
                            <span class="postDate">2 dni temu</span>
                        </div>
                    </header>
                    <main class="postContent">
                        <a href="#">
                            <img class="postThumbnail" src="https://via.placeholder.com/728x90.png?text=Visit   +WhoIsHostingThis.com+Buyers+Guide" alt="Post Thumbnail">
                        </a>
                        <p class="postDesc">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam aliquet tempor augue, in tristique diam feugiat et. Morbi placerat, velit sit amet blandit euismod, leo nisl porttitor tortor, ut fringilla nibh leo ac purus. Phasellus ipsum sapien, dignissim vitae dui nec, convallis iaculis urna. Vivamus congue eget felis tincidunt feugiat...
                        </p>
                    </main>
                    <footer class="postFooter">
                        <output class="postTags row">
                            <a href="#" class="postTag col" data-tool="tooltip" title="{{__('culture.searchTag')}}" data-placement="bottom">Penisy</a>
                        </output>
                        <a href="#" class="postReadMoreBtn">{{__('blog.readMore')}} <i class="fas fa-chevron-right"></i></a>
                        <button class="btn likePostButton" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.like')}}">
                            <i class="fas fa-fire likeIcon"></i>
                            <span class="likesAmount">5</span>
                        </button>
                    </footer>
                </article>
                <hr>
                <article class="blogFeed-post">
                    <header class="postHeader row">
                        <div class="postTitle col-12">
                            <a href="#">Tutuł Posta</a>
                        </div>
                        <div class="postDetails col-12">
                            <span class="postAuthor"><a href="#">To ja Byłem</a></span>
                            <span class="postDate">2 dni temu</span>
                        </div>
                    </header>
                    <main class="postContent">
                        <a href="#">
                            <img class="postThumbnail" src="https://via.placeholder.com/728x90.png?text=Visit   +WhoIsHostingThis.com+Buyers+Guide" alt="Post Thumbnail">
                        </a>
                        <p class="postDesc">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam aliquet tempor augue, in tristique diam feugiat et. Morbi placerat, velit sit amet blandit euismod, leo nisl porttitor tortor, ut fringilla nibh leo ac purus. Phasellus ipsum sapien, dignissim vitae dui nec, convallis iaculis urna. Vivamus congue eget felis tincidunt feugiat...
                        </p>
                    </main>
                    <footer class="postFooter">
                        <output class="postTags row">
                            <a href="#" class="postTag col" data-tool="tooltip" title="{{__('culture.searchTag')}}" data-placement="bottom">Penisy</a>
                        </output>
                        <a href="#" class="postReadMoreBtn">{{__('blog.readMore')}} <i class="fas fa-chevron-right"></i></a>
                        <button class="btn likePostButton" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.like')}}">
                            <i class="fas fa-fire likeIcon"></i>
                            <span class="likesAmount">5</span>
                        </button>
                    </footer>
                </article>
                <hr>
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
                        <output></output>
                    </form>
                </main>
                <footer class="widgetFooter">
                    <button class="btn widgetBtn" type="submit">{{__('searcher.search')}}</button>
                </footer>
            </div>

            <div id="authorsWidget" class="blogWidget">
                <header class="widgetHeader">
                    <span>{{__('blog.authors')}}</span>
                </header>
                <main class="widgetContent row">
                    <span class="col widgetAuthor">
                        <a href="#">Autor</a>
                    </span>
                    <span class="col widgetAuthor">
                        <a href="#">Autor</a>
                    </span>
                    <span class="col widgetAuthor">
                        <a href="#">Autor</a>
                    </span>
                </main>
            </div>

            <div id="categoriesWidget" class="blogWidget">
                <header class="widgetHeader">
                    <span>{{__('blog.categories')}}</span>
                </header>
                <main class="widgetContent row">
                    <span class="col widgetCategory">
                        <a href="#">Dział</a>
                    </span>
                    <span class="col widgetCategory">
                        <a href="#">Dział</a>
                    </span>
                    <span class="col widgetCategory">
                        <a href="#">Dział</a>
                    </span>
                    <span class="col widgetCategory">
                        <a href="#">Dział</a>
                    </span>
                    <span class="col widgetCategory">
                        <a href="#">Dział</a>
                    </span>
                    <span class="col widgetCategory">
                        <a href="#">Dział</a>
                    </span>
                </main>
            </div>

            <div id="calendar">
                Kalendarz
            </div>
        </aside>
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

