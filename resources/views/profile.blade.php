@extends('layouts.app')

@section('content')
    <div class="container-fluid row p-0">
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
                <span>
                    {{__('profile.lastActive')}}
                    @if ($user->status == "online")
                        <span style="font-weight: bold; color: lawngreen !important">{{{__('profile.active')}}}</span>
                    @else
                        {{$user->updated_at->diffForHumans()}}
                    @endif
                </span>
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
                            {{__('profile.status_taken')}}
                        @else
                            {{__('profile.status_free')}}
                        @endif
                    </main>
                </div>

            </div>
            <div class="col-12 userDesc">
                <span>
                    @if (str_word_count($user->description) > 25)
                        {{Illuminate\Support\Str::words($user->description, 25, "...")}}
                        <button class="col-4 mx-auto p-0 mt-1 btn">{{__('profile.readMore')}}</button>
                    @else
                        {{$user->description}}
                    @endif
                </span>
            </div>
            <div class="col-12 userTags row">
                @foreach ($tags as $tag)
                    <span class="col-3">
                        {{$tag}} 
                    </span>
                    @if ($loop->iteration == 6 && $loop->remaining > 0)
                        <button class="col-4 mx-auto p-0 mt-2 btn">{{__('profile.moreContent',['remaining' => $loop->remaining])}}</button>
                        @break
                    @endif
                @endforeach
            </div>
            <div class="col-12 userButtons row">
                <div class="col text-center ico likeProfile">
                    <button class="btn likeBtn @if($user->liked()) active @endif" data-id="{{$user->id}}" data-tool="tooltip" title="{{__('profile.likeUser')}}" data-placement="bottom">
                        <i class="fas fa-fire"></i>
                        <span class="badge likesAmount @if($user->likeCount <= 0) invisible @endif">
                            {{$user->likeCount}}
                        </span>
                    </button>
                </div>
                @if ($user->id != auth()->id())
                    <div class="col ico">
                        <a href="{{route('message.read', ['name' => $user->name])}}" target="__blank">
                            <button class="btn text-reset" data-tool="tooltip" title="{{__('profile.messageUser')}}" data-placement="bottom">
                                <i class="far fa-comment-dots"></i>
                            </button>
                        </a>
                    </div>
                    <div class="col ico addFriend" data-name="{{$user->name}}" id="{{$user->name}}">
                            @if($user->isFriendWith(auth()->user()))
                                <button class="btn text-reset" data-tool="tooltip" title="{{__('profile.addFriend1')}}" data-placement="bottom">
                                    <i class="fas fa-user-friends"></i>
                                </button>
                            @elseif(auth()->user()->hasSentFriendRequestTo($user))
                                <button class="btn text-reset" data-tool="tooltip" title="{{__('profile.addFriend2')}}" data-placement="bottom">
                                    <i class="active fas fa-user-check"></i>
                                </button>
                            @else
                                <button class="btn text-reset" data-tool="tooltip" title="{{__('profile.addFriend3')}}" data-placement="bottom">
                                    <i class="active fas fa-user-plus"></i>
                                </button>
                            @endif
                    </div>
                    <div class="col ico">
                        <button class="btn reportBtn text-reset" data-name="{{$user->name}}" data-tool="tooltip" title="{{__('profile.reportUser')}}" data-placement="bottom">
                            <i class="fas fa-exclamation"></i>
                        </button>
                    </div>
                @endif
            </div>
        </div>
        <div class="text-center col-md-9 col-sm-12 row">
            @if (session()->has('guest'))
                <div class="alert alert-warning" role="alert">
                    <b>{{session()->get('guest')}}</b>
                </div>
            @else
                <div class="col-12 closeFriends">
                    <header data-tool="tooltip" title="{{__('profile.allFriends')}}" data-placement="bottom">
                        {{__('profile.closeFriends',['amount' => count($friends)])}}
                    </header>
                    <main class="row">
                        @foreach ($friends as $friend)
                            <div class="userFriend col">
                                <a href="{{route('ProfileOtherView',['user' => $friend->name])}}">
                                    <img src="{{asset('img/profile-pictures/'.$friend->picture)}}" alt="{{__("profile.photo", ['user' => $friend->name])}}">
                                </a>
                                <a href="{{route('ProfileOtherView',['user' => $friend->name])}}">
                                    <span>{{$friend->name}}</span>
                                </a>
                            </div>
                        @endforeach
                    </main>
                </div>
                <hr>
                <div class="col-12 activity">
                    kek
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var base_url                = "{{url('/')}}";
        var reportUser              = "{{__('searcher.reportUser')}}";
        var reportUserReason        = "{{__('searcher.reportUserReason')}}";
        var reportUserReasonErr     = "{{__('searcher.reportUserReasonErr')}}";
        var reportUserSuccess       = "{{__('searcher.reportUserSuccess')}}";
    </script>
    <script src="{{asset('js/profile.js')}}"></script>
@endpush