@extends('layouts.app')

@section('titleTag')
    <title>
        Safo | Post-Title
    </title>
@endsection

@section('content')
<div class="spinnerOverlay d-none">
    <div class="spinner-border text-warning" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>
<div class="darkOverlay d-none"></div>

<div class="container-fluid px-5 py-3">
    <article id="blogFeed-singlePost">
        <header class="postHeader row">
            <div class="postTitle col-12">
                <h1>Tutuł Posta</h1>
            </div>
            <div class="postDetails col-12">
                <h2 class="postAuthor"><a href="#">To ja Byłem</a></h2>
                <h3 class="postDate">2 dni temu</h3>
            </div>
        </header>
        <main class="postContent">
            <a href="https://via.placeholder.com/728x90.png?text=Visit   +WhoIsHostingThis.com+Buyers+Guide" data-lightbox="postThumbnail" data-title="Post">
                <img class="postThumbnail" src="https://via.placeholder.com/728x90.png?text=Visit   +WhoIsHostingThis.com+Buyers+Guide" alt="Post Thumbnail">
            </a>
            <p class="postDesc">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Nulla vero nostrum recusandae optio, tempore dignissimos blanditiis deleniti corrupti excepturi minus reprehenderit tempora aliquid id, quod amet aliquam, quos quaerat assumenda! Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorem nobis rem provident ipsa, tenetur sequi dolor officiis! Quibusdam aliquid, recusandae accusantium officia debitis veritatis beatae tempore! Perspiciatis debitis quaerat error?
            </p>
        </main>
        <footer class="postFooter">
            <output class="postTags row">
                <a href="#" class="postTag col" data-tool="tooltip" title="{{__('culture.searchTag')}}" data-placement="bottom">Penisy</a>
            </output>
            <button class="btn likePostButton" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.like')}}">
                <i class="fas fa-fire likeIcon"></i>
                <span class="likesAmount">5</span>
            </button>
            <hr>
            <div class="commentsBox">
                <header>
                    <h4>Komentarze</h4>
                </header>
                @auth
                    <form class="commentsForm" data-id="" method="post">
                        <div class="input-group row">
                            <input type="text" name="commentDesc" class="form-control commentsDesc col-11" placeholder="Napisz Komentarz" aria-label="Napisz Komentarz">
                            <div class="input-group-append col-1 commentButtons">
                                <i class="fas fa-user-tag commentUserTag" data-toggle="modal" data-target="#tagUsersModal" data-tool="tooltip" title="{{__('activityWall.tagUser')}}" data-placement="bottom"></i>
                            </div>
                        </div>
                        <output id="commentUserTags" class="row"></output>
                    </form>
                @endauth
                <output class="commentsFeed"  id="feed-1">
                    {{-- @if(count($cultureItem->comments) > 0) 
                        @include('partials.blog.postComments')
                    @endif --}}
                </output>
            </div>
        </footer>
    </article>
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
    $('.commentsDesc:first').emojioneArea({
        pickerPosition: "top",
        placeholder: "Napisz Komentarz",
        inline: false,
        events: {
            keypress: function(editor,e) {
                if (e.keyCode == 13 || e.which == 13) {
                    e.preventDefault();
                    editor.parent().prev().val(this.getText());
                    editor.parent().prev().parent().submit(); 
                }
            }
        }
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

