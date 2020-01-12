@foreach ($friends as $friend)
    <li class="row active friendObject" id="{{$friend->name}}">
        <a href="{{route('ProfileOtherView',['user' => $friend->name])}}" class="col-7">
            <div class="row">
                <div class="col-4 profilePicture">
                    <a href="{{route('ProfileOtherView',['user' => $friend->name])}}">
                        <img src="{{asset('img/profile-pictures/'.$friend->picture)}}">
                    </a>
                </div>
                <div class="col-6 friendName">
                    <a href="{{route('ProfileOtherView',['user' => $friend->name])}}">
                        <span>{{$friend->name}}</span>
                    </a>
                </div>
            </div>
        </a>
        <div class="col-12 friendOptions">
            <span class="deleteFriend" data-name="{{$friend->name}}" id="{{$friend->name}}"><i class="fas fa-user-minus"></i></span>
            <span><a href="{{route('message.read', ['name' => $friend->name])}}" target="__blank"><i class="far fa-comment-dots"></i></a></span>
            <span class="reportBtn" data-name="{{$friend->name}}"><i class="fas fa-exclamation"></i></span>
        </div>
    </li>
@endforeach