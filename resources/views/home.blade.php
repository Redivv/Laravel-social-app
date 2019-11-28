@extends('layouts.app')

@section('content')
<div class="spinnerOverlay d-none">
    <div class="spinner-border text-warning" role="status">
            <span class="sr-only">Loading...</span>
    </div>
</div>
<div id="friendsWall" class="container-fluid mt-4">
    <div class="row text-center">
        <div class="col-3 text-center wallExtraFunctions">
            <span id="wallFetchBtn" class="fetchBtn" data-toggle="tooltip" data-placement="bottom" title="{{__('activityWall.refreshWall')}}"><i class="fas fa-sync"></i></span>
        </div>
        <div class="col-6 friendsWall">
            <div class="friendsWallHeader">
                <h3>{{__('activityWall.friendsWallHeader')}}</h3>
            </div>
            <form id="wallPost" method="post">
                <output id="picture-preview"></output>
                <textarea id="addPost" name="postDesc"></textarea>
                <div class="friendsWallButtons">
                    <span class="additionalButton" data-toggle="tooltip" data-placement="bottom" title="{{__('activityWall.tagUser')}}"><i class="fas fa-user-tag"></i></span>
                    <label for="postPicture" class="additionalButton" data-toggle="tooltip" data-placement="bottom" title="{{__('activityWall.addImage')}}"><i class="far fa-image"></i></label>
                    <input type="file" class="d-none" name="postPicture[]" accept="image/*" id="postPicture" multiple>
                </div>
                <div class="friendsWallSendButton">
                    <button name="sendPost" id="newPostButton" type="submit" class="btn btn-block">{{__('activityWall.createPost')}}</button>
                </div>
            </form>
            <output id="friendsWallFeed" class="">
                @if (count($posts) > 0)
                    @include('partials.friendsWallPosts')
                @else
                    <div class="noContent">{{__('activityWall.noContent')}}</div>
                @endif
            </output>
        </div>
        <div class="col-3 friendsList">
            <div class="friendsListHeader">
                <h3>{{__('activityWall.friendsListHeader')}}</h3>
            </div>
            <hr>
            <output class="friendsListBox">
                <ul class="friendsList">
                    <li class="row active">
                        <div class="col-2 profilePicture">
                            <img src="{{asset('img/profile-pictures/default-picture.png')}}">
                        </div>
                        <div class="col-5 friendName">
                            <span>Reds</span>
                        </div>
                        <div class="col-5 friendOptions">
                            <span><i class="fas fa-user-minus"></i></span>
                            <span><i class="far fa-comment-dots"></i></span>
                            <span><i class="fas fa-exclamation"></i></span>
                        </div>
                    </li>
                </ul>
            </output>
        </div>
    </div>
</div>

@include('partials.postEditModal')

@include('partials.commentEditModal')

@endsection

@push('scripts')

<script>
    var badFileType      = "{{__('chat.badFileType')}}";
    var deletePostMsg    = "{{__('activityWall.deletePost')}}";
    var emptyCommentMsg  = "{{__('activityWall.emptyComment')}}";
    var deleteCommentMsg = "{{__('activityWall.deleteComment')}}";
    var resetImgMsg      =  "{{__('activityWall.resetPictures')}}";
    var baseUrl = "{{url('/')}}";
</script>

<script src="{{asset('js/emoji.js')}}"></script>
<script src="{{asset('js/activityWall.js')}}"></script>

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
