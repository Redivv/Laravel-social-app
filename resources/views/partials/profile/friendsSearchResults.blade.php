@foreach ($friends as $friend)
    <div class="userFriend col">
        <div class="userFriendContainer">
            <img src="{{asset('img/profile-pictures/'.$friend->picture)}}" alt="{{__('profile.photo', ['user' => $friend->name])}}">
            <span>{{$friend->name}}</span>
        </div>
        <input type="hidden" name="userPartner" value="{{$friend->id}}">
    </div>
@endforeach