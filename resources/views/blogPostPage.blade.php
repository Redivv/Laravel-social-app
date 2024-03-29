@extends('layouts.app')

@section('titleTag')
    <title>
        Safo | {{$post->name}}
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
    @if ($post)
        <article id="blogFeed-singlePost">
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
                    <h1>{{$post->name}}</h1>
                </div>
                <div class="postDetails col-12">
                    <h2 class="postAuthor"><a href="{{route('ProfileOtherView',['user' => $post->user->name])}}" target="__blank">{{$post->user->name}}</a></h2>
                    <h3 class="postDate">{{$post->created_at->diffForHumans()}}</h3>
                </div>
            </header>
            <main class="postContent">
                @if ($thumb = json_decode($post->thumbnail)[0])
                    <a href="{{asset('img/blog-pictures/'.$thumb)}}" data-lightbox="postThumbnail" data-title="Post">
                        <img class="postThumbnail" src="{{asset('img/blog-pictures/'.$thumb)}}" alt="Post Thumbnail">
                    </a>
                @endif
                <main class="postDesc">
                    {!!$post->description!!}
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
                <button class="btn likePostButton @auth likeBtn @endauth @if($post->likeCount > 0) active @endif" data-id="{{$post->id}}" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.like')}}">
                    <i class="fas fa-fire likeIcon"></i>
                    <span class="likesAmount @if($post->likeCount == 0) d-none @endif">{{$post->likeCount}}</span>
                </button>
                <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- BlogPost Ad -->
                <ins class="adsbygoogle"
                    style="display:block"
                    data-ad-client="ca-pub-2738699172205892"
                    data-ad-slot="2990247070"
                    data-ad-format="auto"
                    data-full-width-responsive="true"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
                <hr>
                <div class="commentsBox">
                    <header>
                        <h4>{{__('profile.comment')}}</h4>
                    </header>
                    @auth
                        <form class="commentsForm" data-id="{{$post->id}}" method="post">
                            <div class="input-group row">
                                <input type="text" name="commentDesc" class="form-control commentsDesc col-11" placeholder="Napisz Komentarz" aria-label="Napisz Komentarz">
                                <div class="input-group-append col-1 commentButtons">
                                    <i class="fas fa-user-tag commentUserTag" data-toggle="modal" data-target="#tagUsersModal" data-tool="tooltip" title="{{__('activityWall.tagUser')}}" data-placement="bottom"></i>
                                </div>
                            </div>
                            <output id="commentUserTags" class="row"></output>
                        </form>
                    @endauth
                    <output class="commentsFeed"  id="commentsFeed">
                        @if(count($post->comments) > 0) 
                            @include('partials.blog.postComments')
                        @endif
                    </output>
                </div>
            </footer>
        </article>
    @endif
</div>

@include('partials.home.commentEditModal')

@include('partials.home.tagUsersModal')

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
    var baseUrl= "{{url('/')}}";
    var savedChanges        =  "{{__('profile.savedChanges')}}";
    var deleteHobby         =  "{{__('activityWall.deleteTags')}}";
    var confirmMsg          =  "{{__('admin.confirmMsg')}}";
    var badFileType             =  "{{__('chat.badFileType')}}";
    var deleteImages            =  "{{__('activityWall.deleteImages')}}";
    var deleteTags              =  "{{__('activityWall.deleteTags')}}";
    var deletePostMsg           =  "{{__('activityWall.deletePost')}}";
    var emptyCommentMsg         =  "{{__('activityWall.emptyComment')}}";
    var deleteCommentMsg        =  "{{__('activityWall.deleteComment')}}";
    var userNotFound            =  "{{__('activityWall.noUserFound')}}";
    var deleteUserTag           =  "{{__('activityWall.deleteTaggedUser')}}";
    var emptyUser               =  "{{__('activityWall.emptyUser')}}";
    var tagUserMessage          =  "{{__('activityWall.tagUser')}}";
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

