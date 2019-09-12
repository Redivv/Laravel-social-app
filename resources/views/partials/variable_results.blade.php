<div dusk="search_results_box" class="search-results">
    <h3 dusk="search_results_header">
        {{__('searcher.results-age')}}
    </h3>
    @foreach ($resultsVar as $result)
        <div class="searchResult container-fluid mb-4">
            <div class="row">
                <div class="picture col-lg-2">
                    <img src="{{asset('img/profile-pictures/'.$result->picture)}}" alt="">
                    <div class="overlay"></div>
                    <a href="#">
                        <div class="overlay-content fadeIn-bottom">
                            <span class="overlay-text">{{__('searcher.see_profile')}}</span>
                        </div>
                    </a>
                </div>
                <div class="data col-lg-8">
                     <div class="personalInfo-box"><span class="name">{{$result->name}}</span>  <span class="age">{{$result->age}}</span></div>
                     <div class="city-box"><span class="city">{{$result->city}}</span></div>
                     <div class="description-box"><span class="description">{{$result->desc}}</span></div>
                </div>
                @auth
                  <div class="icons col-lg-2">
                    <div class="row">
                        <div class="col ico"><a class="text-reset" href="{{route('message.read', ['id' => $result->id])}}"><i class="far fa-comment-dots"></i></a></div>
                        <div class="col ico"><a class="text-reset" href="#"><i class="fas fa-user-plus"></i></a></div>
                        <div class="col ico"><a class="text-reset" href="#"><i class="fas fa-exclamation"></i></a></div>
                    </div>
                </div>  
                @endauth
            </div> 
        </div>
    @endforeach
</div>