@extends('layouts.app')

@section('titleTag')
    <title>
        {{__('app.cultureSearch')}}
    </title>
@endsection

@section('content')
    <div class="container pt-3">
        <span class="searchMoreToggle" data-toggle="collapse" data-target="#cultureSearchMore" aria-expanded="false" aria-controls="cultureSearchMore">
            {{__('culture.searchMore')}} <i class="fas fa-search"></i>
        </span>
        <form class="collapse" id="cultureSearchMore">
            <div class="formTextFields form-group row">
                <div class="titleSearchBox col-md-7 col-sm-12">
                    <label for="titleSearch">
                        {{__('culture.title')}}
                    </label>
                    <input type="text" class="form-control" name="titleSearch" id="titleSearch" placeholder="{{__('culture.searchName')}}">
                </div>
                <div class="tagSearchBox col-md-5 col-sm-12">
                    <label for="tagSearch">
                        {{__('culture.tags')}}
                    </label>
                    <div class="input-group tagSearch">
                        <input id="tagSearch" type="text" class="form-control" placeholder="{{__('culture.searchTags')}}" aria-label="Tag Name" aria-describedby="tag search button">
                        <div class="input-group-append">
                            <button class="btn" type="button">
                                {{__('searcher.add')}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="formButtons form-group row">
                <label class="col btn sortOptionBtn active">
                    <input type="radio" name="options" id="lettersSort" value="lettersSort" autocomplete="off" checked>
                    {{__('culture.likesSort')}}
                </label>
                <label class="col btn sortOptionBtn">
                    <input type="radio" name="options" id="likesSort" value="likesSort" autocomplete="off">
                    {{__('culture.alfaSort')}}
                </label>
                <label class="col btn sortOptionBtn">
                    <input type="radio" name="options" id="dateSort" value="dateSort" autocomplete="off">
                    {{__('culture.dateSort')}}
                </label>
                <div class="col-12 sortOptionsDir row">
                    <div class="sortOptionsDirBtn col">
                        <input type="radio" name="sortOptionsDir" id="dirAsc" value="asc">
                        <br>
                        <label for="dirAsc">{{__('searcher.asc')}}</label>
                    </div>
                    <div class="sortOptionsDirBtn col">
                        <input type="radio" name="sortOptionsDir" id="dirDesc" checked value="desc">
                        <br>
                        <label for="dirDesc">{{__('searcher.desc')}}</label>
                    </div>
                </div>
            </div>
            <div class="formSubmitBtn form-group row">
                <button type="submit" class="btn">
                    {{__('searcher.search')}}
                </button>
            </div>
        </form>
        <output id="searchResultsOutput">
            <a class="searchResult row" href="#">
                <div class="itemImage col-1">
                    <img src="{{asset('img/profile-pictures/default-picture.png')}}" alt="">
                </div>
                <div class="itemTitle col-2">
                    Persona 4
                </div>
                <div class="itemAttributes col-7 row">
                    <div class="itemAttr col row">
                        <span class="attrTitle col-12">
                            penis
                        </span>
                        <span class="attrValue col-12">
                            penis
                        </span>
                    </div>
                    <div class="itemAttr col row">
                        <span class="attrTitle col-12">
                            penis
                        </span>
                        <span class="attrValue col-12">
                            penis
                        </span>
                    </div>
                    <div class="itemAttr col row">
                        <span class="attrTitle col-12">
                            penis
                        </span>
                        <span class="attrValue col-12">
                            penis
                        </span>
                    </div>
                    <div class="itemAttr col row">
                        <span class="attrTitle col-12">
                            penis
                        </span>
                        <span class="attrValue col-12">
                            penis
                        </span>
                    </div>
                </div>
                <div class="itemLikes col-2">
                    <button class="btn likeBtn">
                        <i class="fas fa-fire"></i>
                        <span class="badge likesAmount active">5</span>
                    </button>
                </div>
            </a>
            <hr>
            <a class="searchResult row" href="#">
                <div class="itemImage col-1">
                    <img src="{{asset('img/profile-pictures/default-picture.png')}}" alt="">
                </div>
                <div class="itemTitle col-2">
                    Rodzinne Spotkanie
                </div>
                <div class="itemAttributes col-7 row">
                    <div class="itemAttr col row">
                        <span class="attrTitle col-12">
                            Twój Stary
                        </span>
                        <span class="attrValue col-12">
                            Pijany
                        </span>
                    </div>
                    <div class="itemAttr col row">
                        <span class="attrTitle col-12">
                            Remiza
                        </span>
                        <span class="attrValue col-12">
                            Rozjebana
                        </span>
                    </div>
                    <div class="itemAttr col row">
                        <span class="attrTitle col-12">
                            Profit
                        </span>
                        <span class="attrValue col-12">
                            Maksymalny
                        </span>
                    </div>
                    <div class="itemAttr col row">
                        <span class="attrTitle col-12">
                            Rozwód
                        </span>
                        <span class="attrValue col-12">
                            W trakcie
                        </span>
                    </div>
                </div>
                <div class="itemLikes col-2">
                    <button class="btn likeBtn">
                        <i class="fas fa-fire"></i>
                        <span class="badge likesAmount active">5</span>
                    </button>
                </div>
            </a>
        </output>
        <section class="extraLinks row">
            <a href="#" class="col">
                <h3>
                    Strona Główna
                </h3>
            </a>
            <a href="#" class="col">
                <h3>
                    Gry
                </h3>
            </a>
            <a href="#" class="col">
                <h3>
                    Filmy
                </h3>
            </a>
            <a href="#" class="col">
                <h3>
                    Muzyka
                </h3>
            </a>
        </section>
    </div>
@endsection

@push('styles')
    <style>
        .navCulture > .nav-link{
            color: #f66103 !important;
        }
    </style>
    <link rel="stylesheet" href="{{asset("jqueryUi\jquery-ui.min.css")}}">
@endpush

@push('scripts')
<script>
    var baseUrl = "{{url('/')}}";
</script>
<script src="{{asset('js/culture.js')}}"></script>

<script src="{{asset("jqueryUi\jquery-ui.min.js")}}"></script>

<script defer>
    Echo.join('online')
        .joining((user) => {
            axios.patch('/api/user/'+ user.name +'/online', {
                    api_token : user.api_token
            });
        })

        .leaving((user) => {
            axios.patch('/api/user/'+ user.name +'/offline', {
                api_token : user.api_token
            });
        })
</script>
@endpush