@extends('layouts.forms')

@section('content')

    <div class="container loginForm">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card login-window shadow">
                    <div class="card-header login-header login-title-size"><img src="img/safo_logo_white.png" alt="Safo"></div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class=" row justify-content-center">
                                <label for="email" class="col-md-4 col-form-label ">{{ __('login.email-address') }}</label>
                            </div>
                            <div class="form-group row justify-content-center">
                                <div class="col-md-6">
                                    <input id="email" type="email" size="30" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                </div>
                            </div>

                            <div class=" row justify-content-center">
                                <label for="password" class="col-md-4 col-form-label">{{ __('login.password') }}</label>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('email') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ __('login.error') }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8 offset-md-2">
                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('login.forgot') }}
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-3">
                                    <div class="">
                                        <input class="" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('login.remember') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="login-button login-title-size">{{ __('login.login-button') }}</button>
                            <div class="registerLink mt-3">Nie Masz Jeszcze Konta?<br><a href="{{route('register')}}">Zarejestruj Się</a></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('styles')
    <style>
        body{
            background-image: url("img/background.jpg");
        }

    </style>
@endpush