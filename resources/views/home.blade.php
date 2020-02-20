@extends('layouts.app')

@section('titleTag')
    <title>
        {{__('app.dashboard')}}
    </title>
@endsection

@section('content')
<div class="spinnerOverlay d-none">
    <div class="spinner-border text-warning" role="status">
            <span class="sr-only">Loading...</span>
    </div>
</div>

<div class="darkOverlay d-none"></div>

<div id="friendsWall" class="container-fluid mt-4 p-0">
    <div class="row text-center">
        <div class="col-3 text-center wallExtraFunctions">
            @include('partials.home.wallExtraFunctions')
        </div>
        <div class="offset-3 col-6 friendsWall">
            <div class="friendsWallHeader">
                <h3>{{__('activityWall.friendsWallHeader')}}</h3>
            </div>
            <form id="wallPost" method="post">
                <output id="picture-preview"></output>
                <textarea id="addPost" name="postDesc"></textarea>
                <output id="postTaggedUsers" class="row"></output>

                <div class="friendsWallButtons">
                    <span class="additionalButton tagUserButton" data-toggle="modal" data-target="#tagUsersModal" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.tagUser')}}">
                        <i class="fas fa-user-tag"></i>
                    </span>
                    <label for="postPicture" class="additionalButton" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.addImage')}}">
                        <i class="far fa-image"></i>
                    </label>
                    <input type="file" class="d-none" name="postPicture[]" accept="image/*" id="postPicture" multiple>

                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="isPublic" class="custom-control-input" id="isPublicSwitch1">
                        <label class="custom-control-label" for="isPublicSwitch1">{{__('activityWall.togglePublic')}}</label>
                    </div>

                    @if (auth()->user()->isAdmin())
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="isAdmin" class="custom-control-input" id="isAdminSwitch1">
                            <label class="custom-control-label" for="isAdminSwitch1">{{__('activityWall.toggleAdmin')}}</label>
                        </div>
                    @endif
                </div>
                <div class="friendsWallSendButton">
                    <button name="sendPost" id="newPostButton" type="submit" class="btn btn-block">{{__('activityWall.createPost')}}</button>
                </div>
            </form>
            <output id="friendsWallFeed" class="p-0">
                @if (count($posts) > 0)
                    @include('partials.home.friendsWallPosts')
                @else
                    <div class="noContent">{{__('activityWall.noContent')}}</div>
                @endif
            </output>
        </div>
        <div class="col-3 friendsList">
            <div class="friendsListHeader">
                <h3>{{__('activityWall.friendsListHeader')}}</h3>
            </div>
            <output class="friendsListBox">
                @if(count($friends)>0)
                    <ul class="friendList">
                        @include('partials.home.friendsList')
                    </ul>
                @else   
                    {{__('activityWall.noFriendsToShow')}}
                @endif
            </output>
        </div>
    </div>
</div>

<a id="scrollUpAnchor" href="#navBar"><i class="fas fa-arrow-up"></i></a>

<span id="showSidePanels"><i class="fas fa-arrows-alt-h"></i></span>

@include('partials.home.postEditModal')

@include('partials.home.commentEditModal')

@include('partials.home.tagUsersModal')

@endsection

@push('styles')
    <style>
        .navHome > .nav-link{
            color: #f66103 !important;
        }
    </style>
    <link rel="stylesheet" href="{{asset("jqueryUi\jquery-ui.min.css")}}">
@endpush

@push('scripts')
<script>
    var badFileType             =  "{{__('chat.badFileType')}}";
    var deleteImages            =  "{{__('activityWall.deleteImages')}}";
    var deleteTags              =  "{{__('activityWall.deleteTags')}}";
    var deletePostMsg           =  "{{__('activityWall.deletePost')}}";
    var emptyCommentMsg         =  "{{__('activityWall.emptyComment')}}";
    var deleteCommentMsg        =  "{{__('activityWall.deleteComment')}}";
    var resetImgMsg             =  "{{__('activityWall.resetPictures')}}";
    var userNotFound            =  "{{__('activityWall.noUserFound')}}";
    var deleteUserTag           =  "{{__('activityWall.deleteTaggedUser')}}";
    var emptyUser               =  "{{__('activityWall.emptyUser')}}";
    var reportUser              =  "{{__('searcher.reportUser')}}";
    var reportUserReason        =  "{{__('searcher.reportUserReason')}}";
    var reportUserReasonErr     =  "{{__('searcher.reportUserReasonErr')}}";
    var reportUserSuccess       =  "{{__('searcher.reportUserSuccess')}}";
    var deleteFriend            =  "{{__('activityWall.deleteFriend')}}";
    var friendDeleted           =  "{{__('activityWall.friendDeleted')}}";
    var tagUserMessage          =  "{{__('activityWall.tagUser')}}";

    var baseUrl = "{{url('/')}}";
</script>

<script src="{{asset('js/emoji.js')}}"></script>
<script src="{{asset('js/activityWall.js')}}"></script>

<script src="{{asset("jqueryUi\jquery-ui.min.js")}}"></script>

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
            
        .listen('UserOnline', (e) => {
            $('.friendObject[data-id="'+e.user.id+'"]').addClass('active');
        })

        .listen('UserOffline', (e) => {
            $('.friendObject[data-id="'+e.user.id+'"]').removeClass('active');
        });
</script>
@endpush
