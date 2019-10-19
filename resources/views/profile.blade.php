@extends('layouts.profile')

@section('name')
<p class="profile-paragraph">
    {{-- Tłumaczenie 'nazwy' --}}
    {{__('profile.username')}}: 
    {{-- pobranie nazwy użytkownika z DB --}}
    {{ $user ->name }}
</p>
    
@endsection
@section('city')
<p class="profile-paragraph">
    {{-- Tłumaczenie 'miasta' --}}
    {{__('profile.city')}}: 
    {{-- Pobieranie miejsca zamieszkania użytkownika z DB (w razie braku System powie, że nie podano) --}}
    @if ( !$user->city_id )
    {{-- Tłumaczenie 'nie podano' --}}
        <i>{{__('profile.city_err')}}</i>
    @else
        {{ $user->city->name }}
    @endif
</p>

@endsection

@auth
@if ($user->id == Auth::user()->id)
    @section('email')
    <p class="profile-paragraph">
        {{-- Tłumaczenie 'Adresu email' --}}
        {{__('profile.email')}}: 
        {{-- pobranie maila użytkownika z DB --}}
        {{ $user ->email }}
    </p>
    @endsection
@endif
@endauth

@section('birth')
<p class="profile-paragraph">
    {{-- Tłumaczenie 'roku ur' --}}
    {{__('profile.birth')}}: 
    {{-- pobranie roku ur użytkownika z DB --}}
    {{ $user ->birth_year }}
</p>

@endsection
@section('photo')
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Tłumaczenie 'zdjęcia' --}}
            <p>{{__('profile.photo')}}:</p>
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
    {{__('profile.desc')}}:
    @if (!$user->description)
        <i>{{__('profile.desc_err')}}</i>
    @else
        <br>
        <div class="desc">
        {{ $user ->description }}
        </div>
    @endif
@endsection

    @section('tags')
        <div class="text-center"><h3>{{__('profile.Tags')}}<h3></div>
        <div class="tagList row mt-3">
            @include('partials.tagList')
        </div>
    @endsection


@section('endform')
<div>
    @auth
        @if($user->id == Auth::user()->id)
        <a href="/profile/edit" style="margin-left:20px;" class="btn form-btn button"><b>{{__('profile.edit')}}</b></a>
        @endif    
    @endauth
    
</div>
@endsection