@extends('layouts.profile')

@section('startform')
    <form action="/profile" method="POST" enctype="multipart/form-data">
    @csrf
    @method('patch')
@endsection


@section('name')
    Nazwa:
    <input id="name" class="form-control" name="name" type="text" value="{{ $user->name}}">
@endsection

@section('city')
    Miasto:
    <input id="city" class="form-control" name="city" type="text" value="{{ $user->city}}">
@endsection

@section('email')
    E-mail:
    {{ $user->email}}
@endsection

@section('birth')
    Rok urodzenia:
    {{ $user->birth_year }}
@endsection

@section('photo')
    Zdjęcie:
    <input id="photo" name="photo" type="file" accept=".png,.jpg">
@endsection

@section('desc')
    Opis:
    <textarea name="description" class="form-control" id="description" cols="30" rows="10">{{ $user->description }}</textarea>
@endsection
@section('endform')
    <button type="submit" class="form-btn btn m-1" >submit changes</button>
    <a href="/profile" class="form-btn btn">Anuluj</a>
    </form>
@endsection