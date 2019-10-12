@extends('layouts.profile')

@section('name')
<p class="profile-paragraph">
    {{-- Tłumaczenie 'nazwy' --}}
    <b>Nazwa: </b>
    {{-- pobranie nazwy użytkownika z DB --}}
    {{ $user ->name }}
</p>
    
@endsection
@section('city')
<p class="profile-paragraph">
    {{-- Tłumaczenie 'miasta' --}}
    <b>Miasto: </b>
    {{-- Pobieranie miejsca zamieszkania użytkownika z DB (w razie braku System powie, że nie podano) --}}
    @if ( !$user ->city )
    {{-- Tłumaczenie 'nie podano' --}}
        <i>Nie podano</i>
    @else
        {{ $user ->city }}
    @endif
</p>

@endsection
@section('email')
<p class="profile-paragraph">
    {{-- Tłumaczenie 'Adresu email' --}}
    <b>Adres E-mail: </b>
    {{-- pobranie maila użytkownika z DB --}}
    {{ $user ->email }}
</p>

@endsection
@section('birth')
<p class="profile-paragraph">
    {{-- Tłumaczenie 'roku ur' --}}
    <b>Rok urodzenia: </b>
    {{-- pobranie roku ur użytkownika z DB --}}
    {{ $user ->birth_year }}
</p>

@endsection
@section('photo')
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Tłumaczenie 'zdjęcia' --}}
            <p><b>Zdjęcie:</b></p>
        </div>
        <div class="col-md-8 foto_frame">
            {{-- pobieranie nazyw zdjęcia z DB i znalezienie go w folderze profile-pictures --}}
            <img class="foto" src="{{asset('img/profile-pictures/'.$user->picture)}}" alt="">
        </div>
    </div>
@endsection
@section('desc')
    {{-- Tłumaczenie 'opis' --}}
    <hr>
    <b>Opis:</b>
    @if (!$user->description)
        <i>Brak opisu</i>
    @else
        <br>
        <div class="desc">
        {{ $user ->description }}
        </div>
    @endif
@endsection

@section('endform')
<div>
    @if($user->id == Auth::user()->id)
    <a href="/profile/edit" style="margin-left:20px;" class="btn form-btn button"><b>Edytuj swój profil</b></a>
    @endif
</div>
@endsection