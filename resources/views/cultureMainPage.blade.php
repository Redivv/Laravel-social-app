@extends('layouts.app')

@section('titleTag')
    <title>
        Safo | {{__('app.culture')}}
    </title>
@endsection


@section('content')
    <div class="container-fluid">
        @if ($errors->any())
        <div class="alert alert-danger mt-3" role="alert">
            @foreach ($errors->all() as $error)
                <div>{{$error}}</div>
            @endforeach
        </div>
        @endif
        <form id="cultureSearch" class="mx-auto" method="get" action="{{route('culture.searchResults')}}">
            <div class="input-group">
                <input type="text" name="titleName" class="form-control" placeholder="{{__('culture.searchName')}}" aria-label="Title Name" aria-describedby="search Button">
                <div class="input-group-append">
                    <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div id="advancedSearch" class="form-group collapse row">
                <div class="advancedTags col-md-6 col-sm-12 tagSearch">
                    <div class="input-group">
                        <input type="text" id="searchTags" class="form-control" placeholder="{{__('culture.searchTags')}}" aria-label="Tag Name" aria-describedby="tag search button">
                        <div class="input-group-append">
                            <button class="btn tagsBtn" type="button">
                                {{__('searcher.add')}}
                            </button>
                        </div>
                    </div>
                    <output id="searchTags-out" class="row"></output>
                </div>
                <div class="col-md-6 col-sm-12 sortOptions row">
                    <label class="col btn sortOptionBtn active">
                        <input type="radio" name="options" id="likesSort" value="likesSort" autocomplete="off" checked>
                        {{__('culture.likesSort')}}
                    </label>
                    <label class="col btn sortOptionBtn">
                        <input type="radio" name="options" id="lettersSort" value="lettersSort" autocomplete="off">
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
                <button class="btn form-btn w-100" type="submit">{{__('searcher.search')}}</button>
            </div>
            <div class="togglerBox">
                <a class="advancedSearchToggle" data-toggle="collapse" href="#advancedSearch" role="button" aria-expanded="false" aria-controls="collapseExample">
                    <span class="toggleArrow"><i class="fas fa-sort-up"></i></span>
                    <div>{{__('culture.advancedSearchToggle')}}</div>
                </a>
            </div>
        </form>
        @if (count($categories) > 0)
            <section id="cultureSections">
                <header>
                    <h3>
                        {{__('culture.sections')}}
                    </h3>
                </header>
                <output id="sectionsOutput" class="row">
                    @foreach ($categories as $cat)
                        <a class="cultureSection col" data-category="{{$cat->name}}">
                            <h4>
                                {{__($cat->name)}}
                            </h4>
                        </a>
                    @endforeach
                </output>
            </section>
        @endif
        @if ( (count($suggest) > 0) && (count($new) > 0) )
            <section id="cultureSugestions" class="row">
                <div id="variableSugestions" class="column col-md-6 col-sm-12">
                    <h4>{{__('culture.sugestion'.$suggest['type'])}}</h4>
                    <output class="sugestions-out" id="variableSugestions-out">
                        @foreach ($suggest['items'] as $item)
                            <a class="cultureItem row" href="{{route('culture.read',['cultureItem' => $item->name_slug])}}">
                                <div class="col-md-4 col-sm-12 itemImg">
                                    <img src="{{asset('img/culture-pictures/'.json_decode($item->thumbnail)[0])}}" alt="Item Image">
                                </div>
                                <div class="col-md-6 col-sm-12 itemDesc">
                                    <h5 class="itemTitle">{{$item->name}}</h5>
                                    <output class="itemAttrs row">
                                        @if ( ($attrValues = json_decode($item->attributes)) && ($attrLabels = json_decode($item->category->attributes) ))
                                            @foreach ($attrLabels as $key => $label)
                                                @if (isset($attrValues[$key]))
                                                <span class="itemAttr col">
                                                    <h6 class="attrLabel font-weight-bold">{{__($label)}}</h6>
                                                    {{$attrValues[$key]}}
                                                </span>
                                                @endif
                                            @endforeach
                                        @endif
                                    </output>
                                </div>
                                <div class="col-md-2 col-sm-12 itemButtons row">
                                    <button class="btn col-12 itemLikeBtn  @if(auth()->check()) likeBtn @if($item->liked()) active @endif @endif" data-id="{{$item->id}}" data-tool="tooltip" title="{{__('culture.likeItem')}}" data-placement="bottom">
                                        <i class="fas fa-fire"></i>
                                        <span class="badge likesCount @if($item->likeCount<=0 ) invisible @endif">
                                            {{$item->likeCount}}
                                        </span>
                                    </button>
                                </div>
                            </a>
                        @endforeach
                    </output>
                </div>
                <div id="newestSugestions" class="column col-md-6 col-sm-12">
                    <h4>{{__('culture.sugestionNew')}}</h4>
                    <output class="sugestions-out" id="newestSugestions-out">
                        @foreach ($new as $item)
                            <a class="cultureItem row" href="{{route('culture.read',['cultureItem' => $item->name_slug])}}">
                                <div class="col-md-4 col-sm-12 mb-sm-2 itemImg">
                                    <img src="{{asset('img/culture-pictures/'.json_decode($item->thumbnail)[0])}}" alt="Item Image">
                                </div>
                                <div class="col-md-6 col-sm-12 mb-sm-2 itemDesc">
                                    <h5 class="itemTitle">{{$item->name}}</h5>
                                    <output class="itemAttrs row">
                                        @if ( ($attrValues = json_decode($item->attributes)) && ($attrLabels = json_decode($item->category->attributes) ))
                                            @foreach ($attrLabels as $key => $label)
                                                @if (isset($attrValues[$key]))
                                                    <span class="itemAttr col">
                                                        <h6 class="attrLabel font-weight-bold">{{__($label)}}</h6>
                                                        {{$attrValues[$key]}}
                                                    </span>
                                                @endif
                                            @endforeach
                                        @endif
                                    </output>
                                </div>
                                <div class="col-md-2 col-sm-12 mt-sm-1 itemButtons row">
                                    <button class="btn col-12 itemLikeBtn @if(auth()->check()) likeBtn @if($item->liked()) active @endif @endif" data-id="{{$item->id}}" data-tool="tooltip" title="{{__('culture.likeItem')}}" data-placement="bottom">
                                        <i class="fas fa-fire"></i>
                                        <span class="badge likesCount @if($item->likeCount<=0 ) invisible @endif">
                                            {{$item->likeCount}}
                                        </span>
                                    </button>
                                </div>
                            </a>
                        @endforeach
                    </output>
                </div>
            </section>
        @endif
        <div class="container-fluid" style="padding: 0 10%">
            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- Culture MainAd -->
            <ins class="adsbygoogle"
                style="display:block"
                data-ad-client="ca-pub-2738699172205892"
                data-ad-slot="4655570588"
                data-ad-format="auto"
                data-full-width-responsive="true"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>
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
    var baseUrl             = "{{url('/')}}";
    var deleteHobby         =  "{{__('activityWall.deleteTags')}}";
    var confirmMsg          =  "{{__('admin.confirmMsg')}}";
</script>

<script src="{{asset("jqueryUi\jquery-ui.min.js")}}"></script>

<script src="{{asset('js/culture.js')}}"></script>

<script src="{{asset('js/emoji.js')}}"></script>

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