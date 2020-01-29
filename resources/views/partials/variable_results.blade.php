<div dusk="search_results_box" class="searchResults-box mt-3">
    <h3 dusk="search_results_header">
        {{$header}}
    </h3>
    @foreach ($resultsVar as $result)
        <div data-id="{{$result->id}}" class="searchResult container-fluid mb-4 @if($result->status == 'online') activeUser @endif">
            <div class="row">
                <div class="picture col-lg-2">
                    <a href="{{route('ProfileView')}}/{{$result->name}}" target="__blank">
                        <img src="{{asset('img/profile-pictures/'.$result->picture)}}" alt="">
                    </a>
                </div>
                <div class="data col-lg-8">
                    <a href="{{route('ProfileView')}}/{{$result->name}}" target="__blank">
                        <div class="personalInfo-box">
                            <span class="name">
                                {{$result->name}}
                            </span>  <span class="age">
                                {{$year - $result->birth_year}}
                            </span>
                        </div>
                    </a>
                    <div class="city-box"><span class="city">{{$result->city}}</span></div>
                    <div class="description-box"><span class="description">{!!nl2br(e(Illuminate\Support\Str::words($result->desc,25,"...")))!!}</span></div>
                </div>
                @auth
                  <div class="icons col-lg-2">
                    <div class="row">
                        <div class="col-4 ico">
                            <a href="{{route('message.read', ['name' => $result->name])}}" target="__blank">
                                <button class="btn text-reset" data-tool="tooltip" title="{{__('profile.messageUser')}}" data-placement="bottom">
                                    <i class="far fa-comment-dots"></i>
                                </button>
                            </a>
                        </div>
                        <div class="col-4 ico addFriend" data-name="{{$result->name}}" id="{{$result->name}}">
                            @if($result->isFriendWith(auth()->user()))
                                <button class="btn text-reset" data-tool="tooltip" title="{{__('profile.addFriend3')}}" data-placement="bottom">
                                    <i class="fas fa-user-friends"></i>
                                </button>
                            @elseif(auth()->user()->hasSentFriendRequestTo($result))
                                <button class="btn text-reset" data-tool="tooltip" title="{{__('profile.addFriend2')}}" data-placement="bottom">
                                    <i class="active fas fa-user-check"></i>
                                </button>
                            @else
                                <button class="btn text-reset" data-tool="tooltip" title="{{__('profile.addFriend1')}}" data-placement="bottom">
                                    <i class="fas fa-user-plus"></i>
                                </button>
                            @endif
                                </button>
                        </div>
                        <div class="col-4 ico">
                            <button class="btn reportBtn text-reset" data-name="{{$result->name}}" data-tool="tooltip" title="{{__('profile.reportUser')}}" data-placement="bottom">
                                <i class="fas fa-exclamation"></i>
                            </button>
                        </div>
                        <div class="col-4 offset-4 text-center ico likeProfile">
                            <button class="btn likeBtn @if($result->liked()) active @endif" data-id="{{$result->id}}" data-tool="tooltip" title="{{__('profile.likeUser')}}" data-placement="bottom">
                                <i class="fas fa-fire"></i>
                                <span class="badge likesAmount @if($result->likeCount <= 0) invisible @endif">{{$result->likeCount}}</span>
                            </button>
                        </div>
                    </div>
                </div>  
                @endauth
            </div> 
        </div>
    @endforeach
</div>