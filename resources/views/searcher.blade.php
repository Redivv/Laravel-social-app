@extends('layouts.app')

@section('content')
    <div class="searcher container mt-3">
        <form class="form" action="{{route('searcher')}}" method="get">
            <div class="form-group">
                <div class="form-row">
                    <div class="col-7">
                        <label for="username">{{__('searcher.username')}}</label>
                        <input type="text" id="username" name="username" aria-label="Nazwa Użytkownika" value="{{old('username')}}" class="form-control @error('username') is-invalid @enderror">
                    </div>
                    <div class="col">
                        <label for="age-min">{{__('searcher.age')}}</label>
                        <div class="input-group">
                            <input id="age-min" name="age-min" type="number" placeholder="Min" min="18" aria-label="{{__('searcher.min-age')}}" value="{{old('age-min')}}" class="form-control @error('age-min') is-invalid @enderror">
                            <input id="age-max" name="age-max" type="number" placeholder="Max" min="18" aria-label="{{__('searcher.max-age')}}" value="{{old('age-max')}}" class="form-control @error('age-max') is-invalid @enderror">
                        </div>
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