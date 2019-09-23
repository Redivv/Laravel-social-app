@extends('layouts.profile')

@section('startform')
<form action="/profile" method="POST">
@csrf
@method('patch')
<input type="hidden" id="id" name="id" value="{{$user->id}}">
@endsection


@section('name')
Nazwa:
<input id="name" name="name" type="text" value="{{ $user->name}}">
@endsection

@section('city')
Miasto:
<input id="city" name="city" type="text" value="{{ $user->city}}">
@endsection

@section('email')
E-mail:
{{-- <input id="email" name="email" type="text" value="{{ $user->email}}"> --}}
@endsection

@section('birth')
Data urodzenia:
{{-- <select id="birth_year" class="form-control" name="birth_year">
        @for ($year = date("Y")-1; $year >= 1950; $year --)
            <option value="{{$year}}">{{$year}}</option>
        @endfor
    </select> --}}
@endsection

@section('photo')
Zdjęcie:
<input type="file" >
@endsection

@section('desc')
<textarea name="description" id="description" cols="30" rows="10">{{ $user->description }}</textarea>
@endsection
@section('endform')
<button type="submit">submit changes</button>

@endsection