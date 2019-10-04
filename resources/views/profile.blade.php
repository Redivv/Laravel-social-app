@extends('layouts.profile')

@section('name')
<p class="profile-paragraph">
    {{-- Tłumaczenie 'nazwy' --}}
    Nazwa: 
    {{-- pobranie nazwy użytkownika z DB --}}
    {{ $user ->name }}
</p>
    
@endsection
@section('city')
<p class="profile-paragraph">
    {{-- Tłumaczenie 'miasta' --}}
    Miasto: 
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
    Adres E-mail: 
    {{-- pobranie maila użytkownika z DB --}}
    {{ $user ->email }}
</p>

@endsection
@section('birth')
<p class="profile-paragraph">
    {{-- Tłumaczenie 'roku ur' --}}
    Rok urodzenia: 
    {{-- pobranie roku ur użytkownika z DB --}}
    {{ $user ->birth_year }}
</p>

@endsection
@section('photo')
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Tłumaczenie 'zdjęcia' --}}
            <p>Zdjęcie:</p>
        </div>
        <div class="col-md-8">
            {{-- pobieranie nazyw zdjęcia z DB i znalezienie go w folderze profile-pictures --}}
            <img src="{{asset('img/profile-pictures/'.$user->picture)}}" alt="">
        </div>
    </div>
@endsection
@section('desc')
    {{-- Tłumaczenie 'opis' --}}
    Opis:
    @if (!$user->description)
        <i>Brak opisu</i>
    @else
        <br>
        {{ $user ->description }}
    @endif
@endsection


@section('endform')
<div>
    @auth
        @if($user->id == Auth::user()->id)
        <a href="/profile/edit" class="btn form-btn">Edytuj swój profil</a>
        @endif    
    @endauth
    
</div>
@endsection