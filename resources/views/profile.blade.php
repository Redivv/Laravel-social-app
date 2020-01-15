@extends('layouts.app')

@section('content')
    <div class="container-fluid row">
        <div class="col-3 text-center profileData row">
            <div class="col-12 userName">
                <span>
                    <i class="fas fa-user"></i>
                    {{$user->name}}
                </span>
            </div>
            <div class="col-12 userPicture">
                <a href="{{asset('img/profile-pictures/'.$user->picture)}}" data-lightbox="Profile" data-title="{{__("profile.photo", ['user' => $user->name])}}">
                    <img src="{{asset('img/profile-pictures/'.$user->picture)}}" alt="{{__("profile.photo", ['user' => $user->name])}}">
                </a>
            </div>
            <div class="col-12 userStatus">
                <span>{{__('profile.lastActive').$user->updated_at->diffForHumans()}}</span>
            </div>
            <div class="col-12 userData row">
                @if ($user->city_id)
                    <div class="col userDataCell">
                        <main>
                            {{$user->city->name}}
                        </main>
                    </div>
                @endif

                <div class="col userDataCell">
                    <main>
                        {{$user->displayAge()}}
                    </main>
                </div>

                <div class="col userDataCell">
                    <main>
                        @if ($user->relationship_status)
                            Zajęta
                        @else
                            Wolna
                        @endif
                    </main>
                </div>

            </div>
            <div class="col-12 userDesc">
                <span>
                    {{$user->description}}
                </span>
            </div>
            <div class="col-12 userTags row">
                @foreach ($tags as $tag)
                    <span class="col-3">
                        {{$tag}} 
                    </span>
                    @if ($loop->iteration == 6 && $loop->remaining > 0)
                        <button class="col-4 mx-auto p-0 mt-1 btn">Więcej</button>
                        @break
                    @endif
                @endforeach
            </div>
            <div class="col-12 userButtons">
                <div class="col text-center ico likeProfile">
                    <button class="btn likeBtn @if($user->liked()) active @endif" data-id="{{$user->id}}">
                        <i class="fas fa-fire"></i>
                        <span class="badge likesAmount @if($user->likeCount <= 0) invisible @endif">
                            {{$user->likeCount}}
                        </span>
                    </button>
                </div>
                @if ($user->id != auth()->id())
                    <div class="col ico">
                        <a href="{{route('message.read', ['name' => $user->name])}}">
                            <button class="btn text-reset">
                                <i class="far fa-comment-dots"></i>
                            </button>
                        </a>
                    </div>
                    <div class="col ico addFriend" data-name="{{$user->name}}" id="{{$user->name}}">
                        <button class="btn text-reset">
                            @if($user->isFriendWith(auth()->user()))
                                <i class="fas fa-user-friends"></i>
                            @elseif($user->hasSentFriendRequestTo(auth()->user()))
                                <i class="fas fa-user-check"></i>
                            @else
                                <i class="fas fa-user-plus"></i>
                            @endif
                        </button>
                    </div>
                    <div class="col ico">
                        <button class="btn reportBtn text-reset" data-name="{{$user->name}}">
                            <i class="fas fa-exclamation"></i>
                        </button>
                    </div>
                @endif
            </div>
        </div>
        <div class="text-center col-md-9 col-sm-12">
            Pek
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('js/profile.js')}}"></script>
@endpush