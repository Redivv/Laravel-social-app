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
@if($user->birth_year!='err0000')
    <p class="profile-paragraph">
        {{-- Tłumaczenie 'miasta' --}}
        {{__('profile.city')}}: 
        {{-- Pobieranie miejsca zamieszkania użytkownika z DB (w razie braku System powie, że nie podano) --}}
        @if ( !$user->city_id )
            {{-- Tłumaczenie 'nie podano' --}}
            <b class='text-danger'>{{__('profile.city_err')}}</b>
        @else
            {{ $user->city->name }}
        @endif
    </p>
@else
@endif
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
    @if (!$user->email_verified_at)
        <div class="alert alert-danger" role="alert">
            {{__('profile.verifyEmailAlert')}}
        </div>
    @endif
@endif
@endauth

@section('birth')
    @if($user->birth_year!='err0000')
        <p class="profile-paragraph">
            {{__('profile.birth')}}: 
            {{-- pobranie roku ur użytkownika z DB --}}
            {{ $user ->birth_year }}
        </p>
    @else
    @endif
@endsection

@section('photo')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <p>{{__('profile.photo')}}:</p>
        </div>
        <div class="col-md-8 foto_frame">
            {{-- pobieranie nazyw zdjęcia z DB i znalezienie go w folderze profile-pictures --}}
            <img class="foto" src="{{asset('img/profile-pictures/'.$user->picture)}}" alt="">
        </div>
        @if ($user->pending_picture)
            <div class="mt-2 alert alert-info" role="alert">
                {{__('profile.pictureInfo')}}
            </div>
        @endif
    </div>
@endsection

@section('desc')
    <hr>
    @if($user->description=='err0000')
        <b class='text-danger'>{{__('profile.access_err')}}</b>
    @else
        {{__('profile.desc')}}:
        @if (!$user->description)
            {{__('profile.desc_err')}}
        @else
            <br>
            <div class="desc">
            {{ $user ->description }}
            </div>
        @endif
    @endif
@endsection
    
@section('tags')
    @if(Auth::check() || $user->hidden_status == 0)
        <div class="text-center"><h3>{{__('profile.Tags')}}<h3></div>
        @if (count($tags) > 0)
            <div class="tagList row mt-3 text-center">
                @include('partials.tagList')
            </div>
        @else
            <div class="text-center text-muted mt-4"><h4>{{__('profile.emptyTags')}}<h4></div>
        @endif
    @endif
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