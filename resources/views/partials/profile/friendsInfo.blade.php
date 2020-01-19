<div class="container">
    @if ($friends && count($friends) > 0)
        <div class="row modalFriends">
            @foreach ($friends as $friend)
                <div class="userFriend col">
                    <a href="{{route('ProfileOtherView',['user' => $friend->name])}}" target="blank">
                        <img src="{{asset('img/profile-pictures/'.$friend->picture)}}" alt="{{__('profile.photo', ['user' => $friend->name])}}">
                        <span>{{$friend->name}}</span>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <span class="noInfo">{{__('profile.emptyModal')}}</span> 
    @endif
</div>