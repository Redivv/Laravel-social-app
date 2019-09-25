@extends('layouts.app')

@section('content')
@yield('startform')
    <div class="container m-5">
        <div class="row justify-content-center">
            <div class="col-md-6 bag-grey p-2">
                    
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <p>
                            @yield('name')
                        </p>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <p>
                            @yield('city')
                        </p>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <p>
                            @yield('email')
                        </p>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <p>
                            @yield('birth')
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 bag-pink"> 
                @yield('photo')
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 bag-yellow">
                <p>
                    @yield('desc')
                </p>
            </div>
        </div>
    </div>
@yield('endform')
@endsection