<h3 class="searchResults-header" dusk="search_results_header">
    @if (count($results) === 0)
        {{__('searcher.not_found')}}
    @else
        {{$header}}
    @endif
</h3>
<div class="searchResults-box mt-3" dusk="search_results_box">
    @foreach ($results as $result)
        <div data-id="{{$result->id}}" class="searchResult container-fluid mb-4 @if($result->status == 'online') activeUser @endif">
            <div class="row">
                <div class="picture col-lg-2">
                    <a href="{{route('ProfileOtherView',['user' => $result->name])}}" target="__blank">
                        <img src="{{asset('img/profile-pictures/'.$result->picture)}}" alt="">
                    </a>
                    <span class="badge activeUserBadge"></span>
                </div>
                <div class="data col-lg-8">
                    <a href="{{route('ProfileOtherView',['user' => $result->name])}}" target="__blank">
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
                          <a href="{{route('message.read', ['name' => $result->name])}}">
                              <button class="btn text-reset" data-tool="tooltip" title="{{__('profile.messageUser')}}" data-placement="bottom">
                                  <i class="far fa-comment-dots"></i>
                              </button>
                          </a>
                      </div>
                      <div class="col-4 ico addFriend" data-name="{{$result->name}}" id="{{$result->name}}">
                          @if($result->isFriendWith(auth()->user()))
                              <button class="btn text-reset active" data-tool="tooltip" title="{{__('profile.addFriend3')}}" data-placement="bottom">
                                  <i class="active fas fa-user-friends"></i>
                              </button>
                          @elseif(auth()->user()->hasSentFriendRequestTo($result))
                              <button class="btn text-reset active" data-tool="tooltip" title="{{__('profile.addFriend2')}}" data-placement="bottom">
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
    {{$results->appends(['username' => request('username') ?? '', 'age-min' => request('age-min') ?? '', 'age-max' => request('age-max') ?? '', 'sortOptions_crit' => request('sortOptions_crit') ?? '', 'sortOptions_dir' => request('sortOptions_dir') ?? '', 'city' => request('city') ?? ''])->links()}}
</div>