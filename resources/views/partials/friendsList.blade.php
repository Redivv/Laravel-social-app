@foreach ($friends as $friend)
    <li class="row active friendObject" id="{{$friend->name}}">
        <a href="../user/profile/{{$friend->name}}" class="col-7">
            <div class="row">
                <div class="col-3 profilePicture">
                    <img src="{{asset('img/profile-pictures/'.$friend->picture)}}">
                </div>
                <div class="col-5 friendName">
                    <span>{{$friend->name}}</span>
                </div>
            </div>
        </a>
        <div class="col-5 friendOptions">
            <span class="deleteFriend" data-name="{{$friend->name}}" id="{{$friend->name}}"><i class="fas fa-user-minus"></i></span>
            <span><a href="{{route('message.read', ['name' => $friend->name])}}" target="__blank"><i class="far fa-comment-dots"></i></a></span>
            <span class="reportBtn" data-name="{{$friend->name}}"><i class="fas fa-exclamation"></i></span>
        </div>
    </li>
@endforeach