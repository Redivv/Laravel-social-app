@extends('layouts.app')

@section('content')
<div class="container m-5">
    @yield('startform')
        <div class="row justify-content-center">
            <div class="col-md-4 bag-grey p-2">
                    
                <div class="row ">
                    <div class="col-md-8">
                        
                            @yield('name')
                        
                            @yield('city')

                            @yield('email')

                            @yield('birth')

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
        <div class="row justify-content-center">
            <div class="col-md-8 p-0">
                <p>
                    @yield('endform')
                </p>
            </div>
        </div>
        
    </div>
@endsection