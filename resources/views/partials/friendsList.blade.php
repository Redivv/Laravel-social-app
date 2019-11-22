@foreach ($friends as $friend)
    <li class="row active">
        <div class="col-2 profilePicture">
            <img src="{{asset('img/profile-pictures/'.$friend->picture)}}">
        </div>
        <div class="col-5 friendName">
            <span>{{$friend->name}}</span>
        </div>
        <div class="col-5 friendOptions">
            <span><i class="fas fa-user-minus"></i></span>
            <span><i class="far fa-comment-dots"></i></span>
            <span><i class="fas fa-exclamation"></i></span>
        </div>
    </li>
@endforeach