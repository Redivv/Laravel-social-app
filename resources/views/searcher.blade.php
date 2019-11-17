@extends('layouts.app')

@section('content')

<div class="spinnerOverlay d-none">
    <div class="spinner-border text-warning" role="status">
            <span class="sr-only">Loading...</span>
    </div>
</div>

<div class="searcher container mt-3">
    {{-- Wyświetla wszystkie powiadomienia typu 'status' z conrtollera --}}
    @if (session('status'))
        <div class="alert alert-info">
            {{ session('status') }}
        </div>
    @endif
    <form class="form" action="{{route('searcher')}}" method="get">
            <div class="form-group">
                <div class="form-row">
                    <div class="col-7">
                        <label for="username">{{__('searcher.username')}}</label>
                        <input type="text" id="username" name="username" aria-label="Nazwa Użytkownika" value="{{request('username')}}" class="form-control @error('username') is-invalid @enderror">
                    </div>
                    <div class="col">
                        <label for="age-min">{{__('searcher.age')}}</label>
                        <div class="input-group">
                            <input id="age-min" name="age-min" type="number" placeholder="Min" min="18" aria-label="{{__('searcher.min-age')}}" value="{{request('age-min')}}" class="form-control @error('age-min') is-invalid @enderror">
                            <input id="age-max" name="age-max" type="number" placeholder="Max" min="18" aria-label="{{__('searcher.max-age')}}" value="{{request('age-max')}}" class="form-control @error('age-max') is-invalid @enderror">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="form-row">
                    <div class="col">
                        <label for="hobby">{{__('searcher.hobby')}}</label>
                        <div class="hobbyCriteria input-group">
                            <input type="text" class="form-control" id="hobby">
                            <button class="btn button rounded-0" type="button">{{__('searcher.add')}}</button>
                        </div>
                        <output id="hobbyOutput">
                            <ul class="row list-group list-group-horizontal">
                                @if (request()->has('hobby'))
                                    @foreach (request('hobby') as $hobby)
                                        <span class="hobby mr-4">
                                            <li>{{str_replace('-',' ',$hobby)}}</li>
                                            <input type="hidden" value="{{$hobby}}" name="hobby[]">
                                        </span>
                                    @endforeach
                                @endif
                            </ul>
                        </output>
                    </div>
                    <div class="col">
                        <label for="sort">{{__('searcher.searchOptions')}}</label>
                        <select class="form-control" name="sortOptions_crit" id="sort">
                            @if (request('sortOptions_crit') == 'created_at')
                                <option value="birth_year">{{__('searcher.age')}}</option>
                                <option value="name">{{__('searcher.username')}}</option>
                                <option value="created_at" selected>{{__('searcher.registerDate')}}</option>
                            @elseif(request('sortOptions_crit') == 'name')
                                <option value="birth_year">{{__('searcher.age')}}</option>
                                <option value="name" selected>{{__('searcher.username')}}</option>
                                <option value="created_at">{{__('searcher.registerDate')}}</option>
                            @else
                                <option value="birth_year" selected>{{__('searcher.age')}}</option>
                                <option value="name">{{__('searcher.username')}}</option>
                                <option value="created_at">{{__('searcher.registerDate')}}</option>
                            @endif
                        </select>
                        <div class="text-center">
                            @if (request('sortOptions_dir') == 'asc')
                                <div class="text-center form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="sortOptions_dir" id="inlineRadio1" value="desc">
                                    <label class="form-check-label" for="inlineRadio1">{{__('searcher.asc')}}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="sortOptions_dir" id="inlineRadio2" checked value="asc">
                                    <label class="form-check-label" for="inlineRadio2">{{__('searcher.desc')}}</label>
                                </div>
                            @else
                                <div class="text-center form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="sortOptions_dir" id="inlineRadio1" checked value="desc">
                                    <label class="form-check-label" for="inlineRadio1">{{__('searcher.asc')}}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="sortOptions_dir" id="inlineRadio2" value="asc">
                                    <label class="form-check-label" for="inlineRadio2">{{__('searcher.desc')}}</label>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col">
                        <label for="city">{{__('searcher.city')}}</label>
                        <select class="form-control" name="city" id="city">
                            <option value="">{{__('searcher.allCities')}}</option>
                            @foreach ($cities as $city)
                                <option value="{{$city->name}}" @if($city->name == request('city')) selected @endif>{{$city->name}}</option>
                            @endforeach
                        </select>         
                    </div>
                </div>
            </div>
            <button class="btn button" type="submit">{{__('searcher.search')}}</button>
        </form>
        <hr>
        @if ($results)
            @include('partials.search_results')
        @elseif ($resultsVar && count($resultsVar) > 0)
            @include('partials.variable_results')
        @else
            @include('partials.error')
        @endif
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
@endpush

@push('scripts')

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
    var base_url                = "{{url('/')}}";
    var deleteMsg               = "{{__('searcher.deleteCriteria')}}";
    var reportUser              = "{{__('searcher.reportUser')}}";
    var reportUserReason        = "{{__('searcher.reportUserReason')}}";
    var reportUserReasonErr     = "{{__('searcher.reportUserReasonErr')}}";
    var reportUserSuccess       = "{{__('searcher.reportUserSuccess')}}";
</script>
<script src="{{asset('js/searcher.js')}}"></script>

<script>
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

        .listen('UserOnline', (e) => {
            $('div.searchResult[data-id="'+e.user.id+'"]').addClass('activeUser');
            this.friend = e.user;
        })
        
        .listen('UserOffline', (e) => {
            $('div.searchResult[data-id="'+e.user.id+'"]').removeClass('activeUser');
            this.friend = e.user;
        });
        
</script>
@endpush