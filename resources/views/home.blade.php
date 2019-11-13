@extends('layouts.app')

@section('content')
<div id="friendsWall" class="container-fluid mt-4">
    <div class="row text-center">
        <div class="col-3">gek</div>
        <div class="col-6 friendsWall">
            <textarea id="addPost" name="addPost" id="post"></textarea>
            <div class="friendsWallButtons">
                <span class="additionalButton" data-toggle="tooltip" data-placement="bottom" title="{{__('activityWall.tagUser')}}"><i class="fas fa-user-tag"></i></span>
                <span class="additionalButton" data-toggle="tooltip" data-placement="bottom" title="{{__('activityWall.addImage')}}"><i class="far fa-image"></i></span>
            </div>
            <div class="friendsWallSendButton">
                <button type="button" name="" id="" class="btn btn-block">{{__('activityWall.createPost')}}</button>
            </div>
            <output id="friendsWallFeed" class="">
                 <div class="noContent">{{__('activityWall.noContent')}}</div>
            </output>
        </div>
        <div class="col-3">
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
@endsection

@push('scripts')

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
