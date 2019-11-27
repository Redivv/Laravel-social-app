@foreach ($friends as $friend)
    <li class="row active">
        <a href="profile/{{$friend->name}}" class="col-7">
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
            <span><i class="fas fa-user-minus"></i></span>
            <span><i class="far fa-comment-dots"></i></span>
            <span><i class="fas fa-exclamation"></i></span>
        </div>
    </li>
@endforeach