@extends('layouts.app')

@section('content')
<div class="spinnerOverlay d-none">
    <div class="spinner-border text-warning" role="status">
            <span class="sr-only">Loading...</span>
    </div>
</div>

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
                    </b>
                </p>
                <p data-id="{{$user->id}}" id="profileStatus" class="text-muted">
                    @if ($user->status == "online")
                        {{__('profile.active')}}
                    @else
                        {{__('profile.lastActive')}} {{$user->updated_at->diffForHumans()}}
                    @endif
                </p>
                <hr>
                <div class="row ">
                    <div class="col-md-10">

                            @yield('name')
                        
                            @yield('city')

                            @yield('email')

                            @yield('birth')
                            
                            @yield('relations')

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                    @yield('buttons')
                    
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
        .joining((user) => {
            axios.patch('/api/user/'+ user.name +'/online', {
                    api_token : user.api_token
            });
        })

        .leaving((user) => {
            axios.patch('/api/user/'+ user.name +'/offline', {
                api_token : user.api_token
            });
        })

        .listen('UserOnline', (e) => {
            if (e.user.id == $('#profileStatus').data('id')) {
                $('#profileStatus').html('{{__("profile.active")}}');
            }
        })

        .listen('UserOffline', (e) => {
            if (e.user.id == $('#profileStatus').data('id')) {
                $('#profileStatus').html('{{__("profile.lastActive1sec")}}');
            }
        });
</script>
@endpush