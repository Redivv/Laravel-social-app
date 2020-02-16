@extends('layouts.app')

@section('titleTag')
    <title>
        {{__('app.culture')}}
    </title>
@endsection

@section('content')
    <div class="container-fluid">
        <form id="cultureSearch" class="mx-auto" method="get" action="{{route('culture.searchResults')}}">
            <div class="input-group">
                <input type="text" name="titleName" class="form-control" placeholder="{{__('culture.searchName')}}" aria-label="Title Name" aria-describedby="search Button">
                <div class="input-group-append">
                    <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div id="advancedSearch" class="form-group collapse row">
                <div class="input-group col-md-6 col-sm-12 tagSearch">
                    <input type="text" class="form-control" placeholder="{{__('culture.searchTags')}}" aria-label="Tag Name" aria-describedby="tag search button">
                    <div class="input-group-append">
                        <button class="btn" type="button">
                            {{__('searcher.add')}}
                        </button>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 sortOptions row">
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
            </div>
            <div class="togglerBox">
                <a class="advancedSearchToggle" data-toggle="collapse" href="#advancedSearch" role="button" aria-expanded="false" aria-controls="collapseExample">
                    <span class="toggleArrow"><i class="fas fa-sort-up"></i></span>
                    <div>{{__('culture.advancedSearchToggle')}}</div>
                </a>
            </div>
        </form>
        <section id="cultureSections">
            <header>
                <h3>
                    {{__('culture.sections')}}
                </h3>
            </header>
            <output id="sectionsOutput" class="row">
                <a class="cultureSection col" href="#">
                    <h4>
                        Gry
                    </h4>
                </a>
                <a class="cultureSection col" href="#">
                    <h4>
                        Filmy
                    </h4>
                </a>
                <a class="cultureSection col" href="#">
                    <h4>
                        Książki
                    </h4>
                </a>
            </output>
        </section>
        <section id="cultureSugestions" class="row">
            <div id="variableSugestions" class="column col-md-6 col-sm-12 border-right">
                <h4>{{__('culture.sugestionTag',['tag' => 'penis'])}}</h4>
                <output class="sugestions-out" id="variableSugestions-out">
                    <a class="cultureItem row" href="#">
                        <div class="col-2 itemImg">
                            <img src="{{asset('img/profile-pictures/default-picture.png')}}" alt="Item Image">
                        </div>
                        <div class="col-7 itemDesc">
                            <h5 class="itemTitle">Penis</h5>
                            <output class="itemAttrs row">
                                <span class="itemAttr col">Strony: 105</span>
                            </output>
                        </div>
                        <div class="col-3 itemButtons row">
                            <button class="btn col-12 likeBtn">
                                <i class="fas fa-fire"></i>
                                <span class="badge likesAmount active">5</span>
                            </button>
                        </div>
                    </a>
                </output>
            </div>
            <div id="newestSugestions" class="column col-md-6 col-sm-12">
                <h4>{{__('culture.sugestionNew')}}</h4>
                <output class="sugestions-out" id="newestSugestions-out">
                    <a class="cultureItem row" href="#">
                        <div class="col-2 itemImg">
                            <img src="{{asset('img/profile-pictures/default-picture.png')}}" alt="Item Image">
                        </div>
                        <div class="col-7 itemDesc">
                            <h5 class="itemTitle">Penis</h5>
                            <output class="itemAttrs row">
                                <span class="itemAttr col">Strony: 105</span>
                            </output>
                        </div>
                        <div class="col-3 itemButtons row">
                            <button class="btn col-12 likeBtn">
                                <i class="fas fa-fire"></i>
                            </button>
                        </div>
                    </a>
                </output>
            </div>
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