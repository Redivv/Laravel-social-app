@extends('layouts.app')

@section('content')
<div class="container m-5 profile_view">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    @yield('startform')
        <div class="row justify-content-center">
            <div class="col-md-4 p-2">
                <p class="profile_title"><i class="fas fa-user"></i>
                    <b>
                        @auth
                            @if($user->id == Auth::user()->id)
                                {{__('profile.your_profile')}}
                            @else
                                {{__('profile.profile')}} {{$user->name}}
                            @endif
                        @else
                        @endauth
                        @guest
                            {{__('profile.profile')}} {{$user->name}}
                        @endguest
                    </b><hr>
                </p>
                <div class="row ">
                    <div class="col-md-10">

                            @yield('name')
                        
                            @yield('city')

                            @yield('email')

                            @yield('birth')

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                    @yield('photo')

                    @yield('status')
            </div>

        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <p>
                    @yield('desc')
                </p>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 p-0">
                <p>
                    @yield('tags')
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
        <div class="row justify-content-center text-center">
            <div class="col-md-8 p-0">
                <p>
                    @yield('tags-form')
                </p>
            </div>
        </div>
        
    </div>
@endsection

@push('scripts')
<script defer>
    Echo.join('online')
</script>
@endpush