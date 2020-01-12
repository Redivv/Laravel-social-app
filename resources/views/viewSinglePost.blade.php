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
            @include('partials.wallExtraFunctions')
        </div>
        <div class="col-6 friendsWall">
            <output id="friendsWallFeed" class="">
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
<script src="{{asset('js/singlePost.js')}}"></script>

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
