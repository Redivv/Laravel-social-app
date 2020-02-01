@foreach ($friends as $friend)
    <li class="row @if($friend->status=="online") active @endif friendObject" id="{{$friend->name}}" data-id="{{$friend->id}}">
        <div class="row">
            <div class="col-4 profilePicture">
                <a href="{{route('ProfileOtherView',['user' => $friend->name])}}">
                    <img src="{{asset('img/profile-pictures/'.$friend->picture)}}" alt="Profile Picture">
                </a>
            </div>
            <div class="col-6 friendName">
                <a href="{{route('ProfileOtherView',['user' => $friend->name])}}">
                    <span>{{$friend->name}}</span>
                </a>
            </div>
        </div>
        <div class="col-12 friendOptions">
            <span class="deleteFriend" data-name="{{$friend->name}}" id="{{$friend->name}}" data-tool="tooltip" title="{{__("activityWall.deleteFriend")}}" data-placement="bottom">
                <i class="fas fa-user-minus"></i>
            </span>
            <span>
                <a href="{{route('message.read', ['name' => $friend->name])}}" target="__blank" data-tool="tooltip" title="{{__('profile.messageUser')}}" data-placement="bottom">
                    <i class="far fa-comment-dots"></i>
                </a>
            </span>
            <span class="reportBtn" data-name="{{$friend->name}}" data-tool="tooltip" title="{{__('profile.reportUser')}}" data-placement="bottom">
                <i class="fas fa-exclamation"></i>
            </span>
        </div>
    </li>
@endforeach