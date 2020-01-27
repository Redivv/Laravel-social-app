@extends('layouts.app')

@section('content')

    <div class="container-fluid">

        @if (session()->has('message'))
            <div class="alert alert-success mt-3" role="alert">
                {{session()->get('message')}}
            </div>
        @endif

        <form method="post" id="settingsForm" class="mt-4">
            @csrf
            @method('patch')
            <div class="profileVisibility group-form row mb-5">
                <legend><h3>{{__('settings.profileVisibility')}}</h3></legend>
                <div class="col form-check form-check-inline row">
                    <input class="form-check-input col-12" type="radio" name="status" id="status0" value="0" @if($user->hidden_status == 0) checked="checked" @endif>
                    <label class="form-check-label col-12" for="status0">
                        {{__('settings.profileVisibility1')}}
                    </label>
                </div>
                <div class="col form-check form-check-inline row">
                    <input class="form-check-input col-12" type="radio" name="status" id="status1" value="1" @if($user->hidden_status == 1) checked @endif>
                    <label class="form-check-label col-12" for="status1">
                        {{__('settings.profileVisibility2')}}
                    </label>
                </div>
                <div class="col form-check form-check-inline row">
                    <input class="form-check-input col-12" type="radio" name="status" id="status2" value="2" @if($user->hidden_status == 2) checked @endif>
                    <label class="form-check-label col-12" for="status2">
                       {{__('settings.profileVisibility3')}}
                    </label>
                </div>
            </div>

            <div class="newsletterSettings group-form row mt-5">
                <legend>
                    <h3>
                        {{__('settings.newsletterSettings')}}
                    </h3>
                </legend>
                <div class="col form-check form-check-inline row">
                    <input class="form-check-input col-12" type="radio" name="newsletter" id="newsletter0" value="1" @if($user->newsletter_status) checked @endif>
                    <label class="form-check-label col-12" for="newsletter0">
                        {{__('settings.newsletterSettings1')}}
                    </label>
                </div>
                <div class="col form-check form-check-inline row">
                    <input class="form-check-input col-12" type="radio" name="newsletter" id="newsletter1" value="0" @if(!$user->newsletter_status) checked @endif>
                    <label class="form-check-label col-12" for="newsletter1">
                        {{__('settings.newsletterSettings2')}}
                    </label>
                </div>
            </div>
            <div class="group-form row mt-5">
                <button class="btn submitButton" type="submit">{{__('settings.save')}}</button>
            </div>
        </form>
        <div class="termsOfService row">
            <a class="termsOf-doc col-6" href="{{asset('files/Regulamin_portalu_Safo.pdf')}}" target="__blank">{{__('registeration.termsOfDoc1')}}</a>
            <a class="termsOf-doc col-6" href="{{asset('files/Polityka_prywatnosci.pdf')}}" target="__blank">{{__('profile.termsOfDoc2')}}</a>
        </div>
    </div>
@endsection