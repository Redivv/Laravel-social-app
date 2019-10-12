@extends('layouts.profile')

@section('startform')
    <form action="/profile" method="POST" enctype="multipart/form-data">
    @csrf
    @method('patch')
@endsection


@section('name')
    {{__('profile.username')}}:
    <input id="name" class="form-control" name="name" type="text" value="{{ $user->name}}">
@endsection

@section('city')
    {{__('profile.city')}}:
    <input id="city" class="form-control" name="city" type="text" value="{{ $user->city}}">
@endsection

@section('email')
    {{__('profile.email')}}:
    {{ $user->email}}
    <br>

@endsection

@section('birth')
    {{__('profile.birth')}}:
    {{ $user->birth_year }}
    <br>
@endsection

@section('photo')
    {{__('profile.photo')}}:
    <input id="photo" style="width:100%;" name="photo" type="file" accept=".png,.jpg">
    <div class="col-md-8 foto_frame" style="margin:20px 0px;">
        <img class="foto" src="{{asset('img/profile-pictures/'.$user->picture)}}" alt="">
    </div>
@endsection

@section('desc')
    {{__('profile.desc')}}:
    <textarea name="description" class="form-control" id="description" cols="30" rows="10">{{ $user->description }}</textarea>
@endsection
@section('endform')
    <button type="submit" style="margin-left:30px !important;" class="form-btn btn m-1 button" >{{__('profile.submit')}}</button>
    <a href="/profile" class="form-btn btn">Anuluj</a>
    </form>
@endsection