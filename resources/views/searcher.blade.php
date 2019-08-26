@extends('layouts.default')

@section('content')
    <div class="container mt-3">
        <form action="{{route('searcher')}}" method="get">
            <div class="form-group">
                <div class="form-row">
                    <div class="col-7">
                        <label for="username">{{__('searcher.username')}}</label>
                        <input type="text" id="username" name="username" aria-label="Nazwa Użytkownika" value="{{old('username')}}" class="form-control @error('username') is-invalid @enderror">
                    </div>
                    <div class="col">
                        <label for="age-min">{{__('searcher.age')}}</label>
                        <div class="input-group">
                            <input id="age-min" name="age-min" type="number" placeholder="Min" min="18" aria-label="Minimalny Wiek" value="{{old('age-min')}}" class="form-control @error('age-min') is-invalid @enderror">
                            <input id="age-max" name="age-max" type="number" placeholder="Max" min="18" aria-label="Maksymalny Wiek" value="{{old('age-max')}}" class="form-control @error('age-max') is-invalid @enderror">
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">{{__('searcher.search')}}</button>
        </form>
        <hr>
        @include('partials.error')
        @if ($results)
            <div dusk="search_results_box" class="search-results">
                <h3 dusk="search_results_header">
                    @if (count($results) === 0)
                        Nie znaleziono użytkowników w podanych kryteriach
                    @else
                        Ilość wyników: {{count($results)}}
                    @endif
                </h3>
                @foreach ($results as $result)
                    {{$result->name}}
                @endforeach
            </div>
        @endif
    </div>
@endsection