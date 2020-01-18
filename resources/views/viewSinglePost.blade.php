@extends('layouts.app')

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
            @include('partials.wallExtraFunctions')
        </div>
        <div class="offset-3 col-6 friendsWall">
            <output id="friendsWallFeed" class="p-0">
                @include('partials.wallSinglePost')
            </output>
        </div>
        <div class="col-3 friendsList">
            <div class="friendsListHeader">
                <h3>{{__('activityWall.friendsListHeader')}}</h3>
            </div>
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

<a id="scrollUpAnchor" href="#navBar"><i class="fas fa-arrow-up"></i></a>

<span id="showSidePanels"><i class="fas fa-arrows-alt-h"></i></span>

@include('partials.postEditModal')

@include('partials.commentEditModal')

@include('partials.tagUsersModal')

@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset("jqueryUi\jquery-ui.min.css")}}">
@endpush

@push('scripts')

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
</script>

<script src="{{asset('js/emoji.js')}}"></script>
<script src="{{asset('js/singlePost.js')}}"></script>

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
</script>
@endpush
