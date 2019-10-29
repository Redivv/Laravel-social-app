@extends('layouts.forms')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header login-info-window">{{ __('app.register') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('registeration.name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="birth_year" class="col-md-4 col-form-label text-md-right">{{ __('registeration.age') }}</label>
                                <div class="col-md-6">
                                    <select id="birth_year" class="form-control @error('birth_year') is-invalid @enderror" name="birth_year" required>
                                        @for ($year = date("Y")-1; $year >= 1950; $year --)
                                            <option value="{{$year}}" @if(old('birth_year') == $year) selected @endif>{{$year}}</option>
                                        @endfor
                                    </select>

                                    @error('birth_year')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ __('registeration.young') }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('registeration.email') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('registeration.password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('registeration.password-confirm') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="profile-picture" class="col-md-4 col-form-label text-md-right">{{ __('registeration.profile-picture') }}</label>

                                <div class="col-md-6">
                                    <input id="profile-picture" type="file" class="form-control-file" name="profile-picture" accept="image/*">
                                    @error('profile-picture')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <output id="picture-preview"></output>
                                    <div class="alert alert-info" role="alert">
                                        {{__('registeration.infoAlert')}}
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" class="form-btn btn btn-block">
                                {{ __('registeration.register-button') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="{{asset('js/register.js')}}"></script>

@endpush
