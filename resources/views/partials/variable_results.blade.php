<div dusk="search_results_box" class="searchResults-box mt-3">
    <h3 dusk="search_results_header">
        {{$header}}
    </h3>
    @foreach ($resultsVar as $result)
        <div data-id="{{$result->id}}" class="searchResult container-fluid mb-4 @if($result->status == 'online') activeUser @endif">
            <div class="row">
                <div class="picture col-lg-2">
                    <img src="{{asset('img/profile-pictures/'.$result->picture)}}" alt="">
                    <div class="overlay"></div>
                    <a href="{{route('ProfileView')}}/{{$result->name}}">
                        <div class="overlay-content fadeIn-bottom">
                            <span class="overlay-text">{{__('searcher.see_profile')}}</span>
                        </div>
                    </a>
                </div>
                <div class="data col-lg-8">
                     <div class="personalInfo-box"><span class="name">{{$result->name}}</span>  <span class="age">{{$year - $result->birth_year}}</span></div>
                     <div class="city-box"><span class="city">{{$result->city}}</span></div>
                     <div class="description-box"><span class="description">{!!nl2br(e($result->desc))!!}</span></div>
                </div>
                @auth
                  <div class="icons col-lg-2">
                    <div class="row">
                        <div class="col-4 ico"><a href="{{route('message.read', ['name' => $result->name])}}"><button class="btn text-reset"><i class="far fa-comment-dots"></i></button></a></div>
                        <div class="col-4 ico addFriend" data-name="{{$result->name}}" id="{{$result->name}}"><button class="btn text-reset">
                                @if($result->friend==2)
                                <i class="fas fa-user-friends"></i>
                                @elseif($result->friend==1)
                                <i class="fas fa-user-check"></i>
                                @else
                                <i class="fas fa-user-plus"></i>
                                @endif
                            </button></div>
                        <div class="col-4 ico"><button class="btn reportBtn text-reset" data-name="{{$result->name}}"><i class="fas fa-exclamation"></i></button></div>
                        <div class="col-12 text-center ico likeProfile"><button class="btn likeBtn @if($result->liked()) active @endif" data-id="{{$result->id}}"><i class="fas fa-fire"></i><span class="badge likesAmount @if($result->likeCount <= 0) invisible @endif">{{$result->likeCount}}</span></button></div>
                    </div>
                </div>  
                @endauth
            </div> 
        </div>
    @endforeach
</div>