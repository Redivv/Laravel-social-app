@extends('layouts.default')

@section('content')
    <div class="container mt-3">
        <form action="{{route('searcher')}}" method="get">
            <div class="form-group">
                <div class="form-row">
                    <div class="col-7">
                        <label for="username">{{__('searcher.username')}}</label>
                        <input type="text" id="username" name="username" aria-label="Nazwa Użytkownika" value="{{old('username')}}" class="form-control">
                    </div>
                    <div class="col">
                        <label for="age-min">{{__('searcher.age')}}</label>
                        <div class="input-group">
                            <input id="age-min" name="age-min" type="number" placeholder="Min" aria-label="Minimalny Wiek" value="{{old('age-min')}}" class="form-control">
                            <input id="age-max" name="age-max" type="number" placeholder="Max" aria-label="Maksymalny Wiek" value="{{old('age-max')}}" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">{{__('searcher.search')}}</button>
        </form>
    </div>
@endsection