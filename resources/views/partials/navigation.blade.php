<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm nav-bar-height">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}"> <!-- url should be changed: /user/home   -->
            <!-- {{ config('app.name', 'default') }} -->
            <img src="/img/safo_logo.jpg" height="50px" alt="Safo">
        </a>

        <ul class="navbar-nav ml-auto mr-auto navMobile">
            <div class="navbarIcons position-relative">

                <li class="nav-item">

                    <a href="#" class="nav-link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="far fa-smile"></i>
                    </a>

                    {{-- User Notifications Mobile --}}
                    <div class="dropdown-menu position-absolute userNotifications">
                        <a class="dropdown-item container" href="#">
                            <div class="row">
                                <div class="notificationImage col-2">
                                    <img class="notificationImage" src="{{asset('img/profile-pictures/default-picture.png')}}" alt="" srcset="">
                                </div>
                                <div class="notificationDesc col-8">
                                    <div class="col-12">Kek</div>
                                    <div class="col-12">Kek</div>
                                </div>
                                
                            </div>
                        </a>
                        <a class="dropdown-item container" href="#">
                            <div class="row">
                                <div class="notificationImage col-2">
                                    <img class="notificationImage" src="{{asset('img/profile-pictures/default-picture.png')}}" alt="" srcset="">
                                </div>
                                <div class="notificationDesc col-8">
                                    <div class="col-12">Kek</div>
                                    <div class="col-12">Kek</div>
                                </div>
                                
                            </div>
                        </a>
                    </div>

                </li>

                <li class="nav-item pr-5 pl-5">

                    <a href="#" class="nav-link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="far fa-comment"></i>
                    </a>

                    {{-- Chat Notifications Mobile --}}
                    <div class="dropdown-menu position-absolute chatNotifications">
                        <a class="dropdown-item container" href="#">
                            <div class="row">
                                <div class="notificationImage col-2">
                                    <img class="notificationImage" src="{{asset('img/profile-pictures/default-picture.png')}}" alt="" srcset="">
                                </div>
                                <div class="notificationDesc col-8">
                                    <div class="col-12">Kek</div>
                                    <div class="col-12">Kek</div>
                                </div>
                            </div>
                        </a>
                        <a class="dropdown-item container" href="#">
                            <div class="row">
                                <div class="notificationImage col-2">
                                    <img class="notificationImage" src="{{asset('img/profile-pictures/default-picture.png')}}" alt="" srcset="">
                                </div>
                                <div class="notificationDesc col-8">
                                    <div class="col-12">Kek</div>
                                    <div class="col-12">Kek</div>
                                </div>
                            </div>
                        </a>
                    </div>

                </li>

                <li class="nav-item">

                    <a href="#" class="nav-link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="far fa-user"></i>
                    </a>

                    {{-- System Notifications Mobile --}}
                    <div class="dropdown-menu systemNotifications position-absolute p-2">
                        <a class="dropdown-item alert alert-info" href="#">
                            Patch note
                        </a>
                        <a class="dropdown-item alert alert-warning" href="#">
                            Ni ma taga
                        </a>
                        <a class="dropdown-item alert alert-danger" href="#">
                            Odrzucono profilowe
                        </a>
                        <a class="dropdown-item alert alert-success" href="#">
                            Przyjęto profilowe
                        </a>
                    </div>

                </li>

            </div>
        </ul>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('app.toggle_nav') }}">
            <span class="navbar-toggler-icon"></span>
        </button>
        

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                
                <!-- Authentication Links -->
                    <li class="nav-item">
                        <a href="{{ url('/searcher') }}" class="nav-link">{{ __('app.searcher') }}</a>
                    </li>
                @auth
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a href="{{ route('adminHome') }}" class="nav-link">{{__('app.adminDashboard')}}</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('home') }}" class="nav-link">{{__('app.dashboard')}}</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ url('/profile') }}" class="nav-link">{{__('app.profile')}}</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/message') }}" class="nav-link">
                            {{__('app.chat')}}
                            <sub id="newMessagesCount" style="color:red">
                                 @if (count($notifications) > 0)
                                    {{count($notifications)}}
                                 @endif
                            </sub>
                        </a>
                    </li>
                @endauth
            </ul>

            <!-- Navbar notification icons -->
            <ul class="navbar-nav ml-auto pr-5 navDesc">
                <div class="navbarIcons position-relative">

                    <li class="nav-item">

                        <a href="#" class="nav-link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="far fa-smile"></i>
                        </a>

                        {{-- User Notifications --}}
                        <div class="dropdown-menu userNotifications">
                            <a class="dropdown-item container" href="#">
                                <div class="row">
                                    <div class="notificationImage col-2">
                                        <img class="notificationImage" src="{{asset('img/profile-pictures/default-picture.png')}}" alt="" srcset="">
                                    </div>
                                    <div class="notificationDesc col-8">
                                        <div class="col-12">Kek</div>
                                        <div class="col-12">Kek</div>
                                    </div>
                                </div>
                            </a>
                            <a class="dropdown-item container" href="#">
                                <div class="row">
                                    <div class="notificationImage col-2">
                                        <img class="notificationImage" src="{{asset('img/profile-pictures/default-picture.png')}}" alt="" srcset="">
                                    </div>
                                    <div class="notificationDesc col-8">
                                        <div class="col-12">Kek</div>
                                        <div class="col-12">Kek</div>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </li>

                    <li class="nav-item">

                        <a href="#" class="nav-link pr-4 pl-4" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="far fa-comment"></i>
                        </a>

                        {{-- Chat Notifications --}}
                        <div class="dropdown-menu chatNotifications">
                            <a class="dropdown-item container" href="#">
                                <div class="row">
                                    <div class="notificationImage col-2">
                                        <img class="notificationImage" src="{{asset('img/profile-pictures/default-picture.png')}}" alt="" srcset="">
                                    </div>
                                    <div class="notificationDesc col-8">
                                        <div class="col-12">Kek</div>
                                        <div class="col-12">Kek</div>
                                    </div>
                                    
                                </div>
                            </a>
                            <a class="dropdown-item container" href="#">
                                <div class="row">
                                    <div class="notificationImage col-2">
                                        <img class="notificationImage" src="{{asset('img/profile-pictures/default-picture.png')}}" alt="" srcset="">
                                    </div>
                                    <div class="notificationDesc col-8">
                                        <div class="col-12">Kek</div>
                                        <div class="col-12">Kek</div>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </li>

                    <li class="nav-item">

                        <a href="#" class="nav-link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="far fa-user"></i>
                        </a>

                        {{-- System Notifications --}}
                        <div class="dropdown-menu systemNotifications p-2">
                            <a class="dropdown-item alert alert-info" href="#">
                                Patch note
                            </a>
                            <a class="dropdown-item alert alert-warning" href="#">
                                Ni ma taga
                            </a>
                            <a class="dropdown-item alert alert-danger" href="#">
                                Odrzucono profilowe
                            </a>
                            <a class="dropdown-item alert alert-success" href="#">
                                Przyjęto profilowe
                            </a>
                        </div>

                    </li>

                </div>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('app.login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('app.register') }}</a>
                        </li>
                    @endif
                @else

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                {{ __('app.logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
@auth
    @push('scripts')
        <script>
            let newMessages = $('#newMessagesCount').html();
            if(newMessages.trim() == ""){
                newMessages = 0;
            }
            var newmsg = function(data) {
                newMessages++;
                $('#newMessagesCount').html(newMessages);
            }
        </script>
            {!! talk_live(['user'=>["id"=>auth()->user()->id, 'callback'=>['newmsg']]]) !!}
    @endpush
@endauth