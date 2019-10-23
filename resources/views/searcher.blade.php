@extends('layouts.app')

@section('content')
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
    var base_url = "{{url('/')}}";
    var deleteMsg = "Czy na pewno chcesz usunąć kryterium?";
</script>
<script src="{{asset('js/searcher.js')}}"></script>

<script defer>
    Echo.join('online')
    .here((users) => {
        this.active_id = new Array();
        users.forEach(function(us){
            active_id.push(us.id);
        })
            let active_idCopy = active_id;
            $('div.searchResult').each(function(){
                if (active_idCopy.length > 1) {
                    if (active_idCopy.includes($(this).data('id'))) {
                        $(this).addClass('activeUser');
                        active_idCopy = active_idCopy.filter(u => (u !== $(this).data('id')));
                    }
                    console.log(active_id);
                }else{
                    return false;
                }
            })
    })
    .joining((user) => {
        this.active_id.push(user.id);
        $('div.searchResult[data-id="'+user.id+'"]').addClass('activeUser');
    })
    .leaving((user) => {
    this.active_id = this.active_id.filter(u => (u !== user.id));
    $('div.searchResult[data-id="'+user.id+'"]').removeClass('activeUser');
    })
</script>
@endpush