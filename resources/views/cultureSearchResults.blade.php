@extends('layouts.app')

@section('titleTag')
    <title>
        Safo | {{__('app.cultureSearch')}}
    </title>
@endsection

@section('content')
<div class="spinnerOverlay d-none">
    <div class="spinner-border text-warning" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>
<div class="darkOverlay d-none"></div>

    <div class="container-fluid pt-3">
        <span class="searchMoreToggle" data-toggle="collapse" data-target="#cultureSearch" aria-expanded="false" aria-controls="cultureSearch">
            {{__('culture.searchMore')}} <i class="fas fa-search"></i>
        </span>
        <form class="collapse" id="cultureSearch" method="get" action="{{route('culture.searchResults')}}">
            @if (request('searchCategory'))
                <input id="searchCategory-data" type="hidden" name="searchCategory" value="{{request('searchCategory')}}">
            @endif
            <div class="formTextFields form-group row">
                <div class="titleSearchBox col-md-7 col-sm-12">
                    <label for="titleSearch">
                        {{__('culture.title')}}
                    </label>
                    <input type="text" class="form-control" name="titleName" id="titleSearch" value="{{request('titleName')}}" placeholder="{{__('culture.searchName')}}">
                </div>
                <div class="tagSearchBox col-md-5 col-sm-12">
                    <label for="searchTags">
                        {{__('culture.tags')}}
                    </label>
                    <div class="input-group tagSearch">
                        <input id="searchTags" type="text" class="form-control" placeholder="{{__('culture.searchTags')}}" aria-label="Tag Name" aria-describedby="tag search button">
                        <div class="input-group-append">
                            <button class="btn tagsBtn" type="button">
                                {{__('searcher.add')}}
                            </button>
                        </div>
                    </div>
                    <output id="searchTags-out" class="row">
                        @if (request('itemTags'))
                            @foreach (request('itemTags') as $tag)
                                @if ($tag)
                                    <div class="col itemTag" data-tool="tooltip" data-placement="bottom" title="{{__('activityWall.deleteTags')}}">
                                        <span>{{$tag}}</span>
                                        <input type="hidden" name="itemTags[]" value="{{$tag}}">
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </output>
                </div>
            </div>
            <div class="formButtons form-group row">
                <label class="col btn sortOptionBtn @if( (request('options') != "lettersSort") && (request('options') != "dateSort") ) active @endif">
                    <input type="radio" name="options" id="likesSort" value="likesSort" autocomplete="off" @if( (request('options') != "lettersSort") && (request('options') != "dateSort") ) checked @endif>
                    {{__('culture.likesSort')}}
                </label>
                <label class="col btn sortOptionBtn @if(request('options') == "lettersSort") active @endif">
                    <input type="radio" name="options" id="lettersSort" value="lettersSort" autocomplete="off" @if(request('options') == "lettersSort") checked @endif>
                    {{__('culture.alfaSort')}}
                </label>
                <label class="col btn sortOptionBtn @if(request('options') == "dateSort") active @endif">
                    <input type="radio" name="options" id="dateSort" value="dateSort" autocomplete="off" @if(request('options') == "dateSort") checked @endif>
                    {{__('culture.dateSort')}}
                </label>
                <div class="col-12 sortOptionsDir row">
                    <div class="sortOptionsDirBtn col">
                        <input type="radio" name="sortOptionsDir" id="dirAsc" value="asc" @if(request('sortOptionsDir') == "asc") checked @endif>
                        <br>
                        <label for="dirAsc">{{__('searcher.asc')}}</label>
                    </div>
                    <div class="sortOptionsDirBtn col">
                        <input type="radio" name="sortOptionsDir" id="dirDesc" @if(request('sortOptionsDir') == "desc") checked @endif value="desc">
                        <br>
                        <label for="dirDesc">{{__('searcher.desc')}}</label>
                    </div>
                </div>
            </div>
            <div class="formSubmitBtn form-group row">
                <button type="submit" class="btn form-btn w-100">
                    {{__('searcher.search')}}
                </button>
            </div>
        </form>
        <output id="searchResultsOutput">
            @if (count($results) > 0)
                @foreach ($results as $item)
                <div class="resultBox">
                    @auth
                        @if (auth()->user()->isAdmin())
                            <div class="col-12 adminButtons">
                                <a href="{{route('adminCulture')."?elementType=cultureItem&elementId=".$item->id}}" data-tool="tooltip" title="{{__('admin.edit')}}" data-placement="bottom">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="post" action="#" class="deleteItem">
                                    @method('delete')
                                    <input type="hidden" name="elementId" value="{{$item->id}}">
                                    <button class="btn" type="submit" data-tool="tooltip" title="{{__('admin.delete')}}" data-placement="bottom">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                    <a class="searchResult row" href="{{route('culture.read',['cultureItem' => $item->name_slug])}}">
                        <div class="itemImage col-md-2 col-sm-6">
                            <img src="{{asset('img/culture-pictures/'.json_decode($item->thumbnail)[0])}}" alt="">
                        </div>
                        <h5 class="itemTitle col-md-2 col-sm-6">
                            {{$item->name}}
                        </h5>
                        <div class="itemAttributes col-md-6 col-sm-12  row">
                            @if ( ($attrValues = json_decode($item->attributes)) && ($attrLabels = json_decode($item->category->attributes) ))
                                @foreach ($attrLabels as $key => $label)
                                    @if (isset($attrValues[$key]))
                                    <div class="itemAttr col row">
                                        <h6 class="attrTitle col-12">
                                            {{__($label)}}
                                        </h6>
                                        <span class="attrValue col-12">
                                            {{$attrValues[$key]}}
                                        </span>
                                    </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        <div class="itemLikes col-md-2 col-sm-12 mt-sm-2">
                            <button class="btn itemLikeBtn @if(auth()->check()) likeBtn @if($item->liked()) active @endif @endif" data-id="{{$item->id}}" data-tool="tooltip" title="{{__('culture.likeItem')}}" data-placement="bottom">
                                <i class="fas fa-fire"></i>
                                <span class="badge likesCount @if($item->likeCount<=0 ) invisible @endif">
                                    {{$item->likeCount}}
                                </span>
                            </button>
                        </div>
                    </a>
                    <hr>
                </div>
                @endforeach
            @else
                <div class="noSearchResults">{{__('chat.noResults')}}</div>
            @endif
        </output>
        @php
            $resultsAppend = [
                'titleName' => request('titleName') ?? '',
                'itemTags' => request('itemTags') ?? null,
                'options' => request('options') ?? '',
                'sortOptionsDir' => request('sortOptionsDir') ?? '',
            ];
            $results = $results->appends($resultsAppend);
        @endphp
        {{$results->links()}}
        @if ($categories)
            <section class="extraLinks row">
                <a class="cultureSection col @if(!request('searchCategory')) active @endif" data-category="all">
                    <h3>
                        {{__('culture.allCats')}}
                    </h3>
                </a>
                @foreach ($categories as $cat)
                    <a class="cultureSection @if(request('searchCategory') == $cat->name_slug) active @endif col" data-category="{{$cat->name}}">
                        <h3>
                            {{__($cat->name)}}
                        </h3>
                    </a>
                @endforeach
            </section>
        @endif
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
    var savedChanges        =  "{{__('profile.savedChanges')}}";
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