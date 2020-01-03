@extends('layouts.app')

@section('floatingPopUps')
    <div id="wallFetchBtn" class="newPostsBox d-none">
        <span class="fetchBtn">{{__('activityWall.refreshWall')}}</span>
    </div>
@endsection

@section('content')
<div class="spinnerOverlay d-none">
    <div class="spinner-border text-warning" role="status">
            <span class="sr-only">Loading...</span>
    </div>
</div>

<div id="friendsWall" class="container-fluid mt-4">
    <div class="row text-center">
        <div class="col-3 text-center wallExtraFunctions">
        </div>
        <div class="col-6 friendsWall">
            <div class="friendsWallHeader">
                <h3>{{__('activityWall.friendsWallHeader')}}</h3>
            </div>
            <form id="wallPost" method="post">
                <output id="picture-preview"></output>
                <textarea id="addPost" name="postDesc"></textarea>
                <output id="postTaggedUsers" class="row"></output>

                <div class="friendsWallButtons">
                    <span class="additionalButton tagUserButton" data-toggle="modal" data-target="#tagUsersModal"><i class="fas fa-user-tag"></i></span>
                    <label for="postPicture" class="additionalButton"><i class="far fa-image"></i></label>
                    <input type="file" class="d-none" name="postPicture[]" accept="image/*" id="postPicture" multiple>

                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="isPublic" class="custom-control-input" id="isPublicSwitch1">
                        <label class="custom-control-label" for="isPublicSwitch1">{{__('activityWall.togglePublic')}}</label>
                    </div>
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
                @if(count($friends)>0)
                    <ul class="friendList">
                        @include('partials.friendsList')
                    </ul>
                @else   
                    {{__('activityWall.noFriendsToShow')}}
                @endif
            </output>
        </div>
    </div>
</div>

@include('partials.postEditModal')

@include('partials.commentEditModal')

@include('partials.tagUsersModal')

@endsection

@push('styles')
<style>
    .navHome > .nav-link{
        color: #f66103 !important;
    }
</style>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
@endpush

@push('scripts')

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
    var badFileType             =  "{{__('chat.badFileType')}}";
    var deletePostMsg           =  "{{__('activityWall.deletePost')}}";
    var emptyCommentMsg         =  "{{__('activityWall.emptyComment')}}";
    var deleteCommentMsg        =  "{{__('activityWall.deleteComment')}}";
    var resetImgMsg             =  "{{__('activityWall.resetPictures')}}";
    var userNotFound            =  "{{__('activityWall.noUserFound')}}";
    var deleteUserTag           =  "{{__('activityWall.deleteTaggedUser')}}";
    var emptyUser               =  "{{__('activityWall.emptyUser')}}";
    var reportUser              = "{{__('searcher.reportUser')}}";
    var reportUserReason        = "{{__('searcher.reportUserReason')}}";
    var reportUserReasonErr     = "{{__('searcher.reportUserReasonErr')}}";
    var reportUserSuccess       = "{{__('searcher.reportUserSuccess')}}";
    var deleteFriend            = "{{__('activityWall.deleteFriend')}}";
    var friendDeleted           = "{{__('activityWall.friendDeleted')}}";

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
