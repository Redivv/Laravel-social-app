@foreach ($friends as $friend)
    <li class="row active">
        <a href="../profile/{{$friend->name}}" class="col-7">
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
            <span><a href="../friends/delete/{{$friend->name}}"><i class="fas fa-user-minus"></i></a></span>
            <span><a href=""><i class="far fa-comment-dots"></i></a></span>
            <span><a><i class="fas fa-exclamation"></i></a></span>
        </div>
    </li>
@endforeach