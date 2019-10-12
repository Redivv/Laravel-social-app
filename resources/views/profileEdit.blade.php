@extends('layouts.profile')

@section('startform')
    <form action="/profile" method="POST" enctype="multipart/form-data">
    @csrf
    @method('patch')
@endsection


@section('name')
    <b>Nazwa:</b>
    {{ $user->name}}
    <br>
@endsection

@section('city')
    <b>Miasto:</b>
    <input id="city" class="form-control" name="city" type="text" value="{{ $user->city}}">
@endsection

@section('email')
    <b>E-mail:</b>
    {{ $user->email}}
    <br>

@endsection

@section('birth')
    <b>Rok urodzenia:</b>
    {{ $user->birth_year }}
    <br>
@endsection

@section('photo')
    <b>Zdjęcie:</b>
    <input id="photo" style="width:100%;" name="photo" type="file" accept=".png,.jpg">
    <div class="col-md-8 foto_frame" style="margin:20px 0px;">
        <img class="foto" src="{{asset('img/profile-pictures/'.$user->picture)}}" alt="">
    </div>
@endsection

@section('desc')
    <b>Opis:</b>
    <textarea name="description" class="form-control" id="description" cols="30" rows="10">{{ $user->description }}</textarea>
@endsection
@section('endform')
    <button type="submit" style="margin-left:30px !important;" class="form-btn btn m-1 button" >Zatwierdź zmiany</button>
    <a href="/profile" class="form-btn btn button">Anuluj</a>
    </form>
@endsection