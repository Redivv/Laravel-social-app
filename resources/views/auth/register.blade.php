@extends('layouts.forms')

@section('titleTag')
    <title>
        Safo | {{config('app.name','Safo')}}
    </title>
@endsection

@section('content')

    <header class="container-fluid">
        @if (session()->has('deletedProfile'))
            <div class="alert alert-success mt-3" role="alert">
                <b>{{session()->get('deletedProfile')}}</b>
            </div>
        @endif
        <div class="pageLogo text-center">
            <img src="{{asset('img/safo_logo.png')}}" alt="Safo Logo" srcset="">
        </div>
        <div class="pageTestimonial">
            <h2 class="testimonial">{{__('registeration.testimonial')}}</h2>
        </div>
    </header>

    <main class="registerForm mt-4 mb-4">
        <div class="formContainer card">
            <div class="card-body">
                <div class="formHeader card-title">
                    <h3>{{__('registeration.joinUs')}}</h3>
                </div>
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf
    
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('registeration.name') }}</label>
    
                        <div class="col-md-7">
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
                        <div class="col-md-7">
                            <select id="birth_year" class="form-control @error('birth_year') is-invalid @enderror" name="birth_year" required>
                                @for ($year = date("Y"); $year >= 1950; $year --)
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
    
                        <div class="col-md-7">
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
    
                        <div class="col-md-7">
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
    
                        <div class="col-md-7">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>
    
                    <div class="form-group row">
                        <label for="profile-picture" class="col-md-4 col-form-label text-md-right">{{ __('registeration.profile-picture') }}</label>
    
                        <div class="col-md-7">
                            <input required id="profile-picture" type="file" class="form-control-file" name="profile-picture" accept="image/*">
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
    
                    <div class="form-group row termsOfContainer">
                        <label for="termsOfService" class="col-md-8 form-check-label text-md-right">
                            {{__('registeration.termsOf')}}
                            <a class="termsOf-doc" href="{{asset('files/Regulamin_portalu_Safo.pdf')}}" target="__blank">{{__('registeration.termsOfDoc1')}}</a>
                            &
                            <a class="termsOf-doc" href="{{asset('files/Polityka_prywatnosci.pdf')}}" target="__blank">{{__('registeration.termsOfDoc2')}}</a>
                        </label>
                        <div class="col-md-4 termsOfInput">
                            <input id="termsOfService" type="checkbox" class="form-check-input" name="termsOfService" required>
                            @error('termsOfService')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    @if (session()->has('message'))
                        <div class="alert alert-danger mt-3" role="alert">
                            <b>{{session()->get('message')}}</b>
                        </div>
                    @endif
                    
                    <button type="submit" class="form-btn btn btn-block">
                        {{ __('registeration.register-button') }}
                    </button>
                    <div class="registerMailInfo">{{__('registeration.mailInfo')}}</div>
                    <div class="loginLink mt-2">{{__('registeration.loginLink1')}} <a href="{{route('login')}}">{{__('registeration.loginLink2')}}</a></div>
                </form>
            </div>
        </div>        
    </main>

    <footer class="additionalLinks row">
        <a href="{{route('culture.mainPage')}}" class="btn">{{__('registeration.cultureLink')}}</a>
        <a href="{{route('blog.mainPage')}}" class="btn">{{__('registeration.blogLink')}}</a>
        <a href="{{route('searcher')}}" class="btn">{{__('registeration.searcherLink')}}</a>
    </footer>
@endsection

@push('scripts')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="{{asset('js/register.js')}}"></script>

@endpush
