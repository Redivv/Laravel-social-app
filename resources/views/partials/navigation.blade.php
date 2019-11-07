<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm nav-bar-height">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}"> <!-- url should be changed: /user/home   -->
            <!-- {{ config('app.name', 'default') }} -->
            <img src="/img/safo_logo.jpg" height="50px" alt="Safo">
        </a>

        @auth
        <ul class="navbar-nav ml-auto mr-auto navMobile">
            <div class="navbarIcons position-relative">

                <li class="nav-item">

                    <a href="#" class="nav-link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="far fa-smile"></i><span class="badge userNotificationsCount badge-pill badge-warning">@if(count($notifications['chat']) > 0){{count($notifications['chat'])}}@endif</span>
                    </a>

                    {{-- User Notifications Mobile --}}
                    <div class="dropdown-menu pl-2 pr-2 position-absolute userNotifications">
                        @if (count($notifications['user']) == 0)
                            <div class="text-center">Brak Powiadomień</div>
                        @endif
                    </div>

                </li>

                <li class="nav-item pr-5 pl-5">

                    <a href="#" class="nav-link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="far fa-comment"></i>
                        <span class="badge chatNotificationsCount badge-pill badge-warning">@if(count($notifications['chat']) > 0){{count($notifications['chat'])}}@endif</span>
                    </a>

                    {{-- Chat Notifications Mobile --}}
                    <div class="dropdown-menu pl-2 pr-2 position-absolute chatNotifications">
                        @if (count($notifications['chat']) == 0)
                            <div class="text-center">Brak Powiadomień</div>
                        @endif
                        @foreach ($notifications['chat'] as $chatNot)
                            <a class="chat-{{$chatNot['data']['sender_id']}} dropdown-item container" href="/message/{{$chatNot['senderName']}}">
                                <div class="row">
                                    <div class="notificationImage col-2">
                                        <img class="notificationImage" src="{{asset('img/profile-pictures/'.$chatNot['senderPicture'])}}" alt="" srcset="">
                                    </div>
                                    <div class="notificationDesc col-8">
                                        <div class="col-12 font-weight-bold">{{$chatNot['senderName']}}</div>
                                        <div class="col-12">{{$chatNot['data']['message_body']}}</div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                </li>

                <li class="nav-item">

                    <a href="#" class="nav-link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="far fa-user"></i><span class="badge systemNotificationsCount badge-pill badge-warning">@if(count($notifications['system']) > 0){{count($notifications['system'])}}@endif</span>
                    </a>

                    {{-- System Notifications Mobile --}}
                    <div class="dropdown-menu pl-2 pr-2 systemNotifications position-absolute p-2">
                        @if (count($notifications['system']) == 0)
                            <div class="text-center">Brak Powiadomień</div>
                        @endif
                        @foreach ($notifications['system'] as $sysNot)
                            @switch($sysNot['type'])
                                @case('App\Notifications\NewProfilePicture')
                                    <a class="{{$sysNot['id']}} dropdown-item alert alert-info" href="/admin/home">
                                        Zgłoszono Nowe Zdjęcie Profilowe
                                    </a>
                                    @break
                                @case('App\Notifications\UserFlagged')
                                    <a class="{{$sysNot['id']}} dropdown-item alert alert-info" href="/admin/home">
                                        Użytkownik Został Zgłoszony
                                    </a>
                                    @break
                                @case('App\Notifications\AcceptedPicture')
                                    <a class="dropdown-item alert alert-success" href="/profile">
                                        Przyjęto profilowe
                                    </a>
                                    @break
                                @case('App\Notifications\DeniedPicture')
                                    <a class="dropdown-item alert alert-danger" href="/profile">
                                        Odrzucono profilowe
                                    </a>
                                    @break
                                @default
                                    
                            @endswitch
                        @endforeach
                    </div>

                </li>

            </div> 
        </ul>
        @endauth
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
                        </a>
                    </li>
                @endauth
            </ul>

            @auth
            <!-- Navbar notification icons -->
            <ul class="navbar-nav ml-auto pr-5 navDesc">
                <div class="navbarIcons position-relative">

                    <li class="nav-item">

                        <a href="#" class="nav-link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="far fa-smile"></i><span class="badge userNotificationsCount badge-pill badge-warning">@if(count($notifications['user']) > 0){{count($notifications['chat'])}}@endif</span>
                        </a>

                        {{-- User Notifications --}}
                        <div class="dropdown-menu userNotifications">
                            @if (count($notifications['user']) == 0)
                                <div class="text-center">Brak Powiadomień</div>
                            @endif
                        </div>

                    </li>

                    <li class="nav-item">

                        <a href="#" class="nav-link pr-4 pl-4" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="far fa-comment"></i>
                            <span id="desktopTalk" class="badge chatNotificationsCount badge-pill badge-warning">@if(count($notifications['chat']) > 0){{count($notifications['chat'])}}@endif</span>
                        </a>

                        {{-- Chat Notifications --}}
                        <div class="dropdown-menu chatNotifications">
                            @if (count($notifications['chat']) == 0)
                                <div class="text-center">Brak Powiadomień</div>
                            @endif
                            @foreach ($notifications['chat'] as $chatNot)
                            <a class="chat-{{$chatNot['data']['sender_id']}} dropdown-item container" href="/message/{{$chatNot['senderName']}}">
                                <div class="row">
                                    <div class="notificationImage col-2">
                                        <img class="notificationImage" src="{{asset('img/profile-pictures/'.$chatNot['senderPicture'])}}" alt="" srcset="">
                                    </div>
                                    <div class="notificationDesc col-8">
                                        <div class="col-12 font-weight-bold">{{$chatNot['senderName']}}</div>
                                        <div class="col-12">{{$chatNot['data']['message_body']}}</div>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>

                    </li>

                    <li class="nav-item">

                        <a href="#" class="nav-link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="far fa-user"></i><span id="descSys" class="badge systemNotificationsCount badge-pill badge-warning">@if(count($notifications['system']) > 0){{count($notifications['system'])}}@endif</span>
                        </a>

                        {{-- System Notifications --}}
                        <div class="dropdown-menu systemNotifications p-2">
                            @if (count($notifications['system']) == 0)
                                <div class="text-center">Brak Powiadomień</div>
                            @endif
                            @foreach ($notifications['system'] as $sysNot)
                                @switch($sysNot['type'])
                                    @case('App\Notifications\NewProfilePicture')
                                        <a class="{{$sysNot['id']}} dropdown-item alert alert-info" href="/admin/home">
                                            Zgłoszono Nowe Zdjęcie Profilowe
                                        </a>
                                        @break
                                    @case('App\Notifications\UserFlagged')
                                        <a class="{{$sysNot['id']}} dropdown-item alert alert-info" href="/admin/home">
                                            Użytkownik Został Zgłoszony
                                        </a>
                                        @break
                                    @case('App\Notifications\AcceptedPicture')
                                        <a class="dropdown-item alert alert-success" href="/profile">
                                            Przyjęto profilowe
                                        </a>
                                        @break
                                    @case('App\Notifications\DeniedPicture')
                                        <a class="dropdown-item alert alert-danger" href="/profile">
                                            Odrzucono profilowe
                                        </a>
                                        @break
                                    @default
                                        
                                @endswitch
                            @endforeach
                        </div>

                    </li>

                </div>
            </ul>
            @endauth

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
            Echo.private('users.'+window.Laravel.user)

            .notification((notification) => {
                let currentAmountNot = $('#descSys').text();

                let html = '<a class="'+notification.id+' dropdown-item alert alert-info" href="/admin/home">'+
                                        'Zgłoszono Nowe Zdjęcie Profilowe'+
                            '</a>';

                switch (notification.type.replace(/\\/g,"/")) {
                    case 'App/Notifications/NewProfilePicture':
                        $('.systemNotificationsCount').html(parseInt(currentAmountNot)+1);
                        $('.systemNotifications').prepend(html);
                        break;
                    case 'App/Notifications/UserFlagged':
                        $('.systemNotificationsCount').html(parseInt(currentAmountNot)+1);
                        $('.systemNotifications').prepend(html);
                        break;
                    
                }
        
            });
        </script>

        <script>
            let newMessages = $('#desktopTalk').html();
            if(newMessages.trim() == ""){
                newMessages = 0;
            }
            var newmsg = function(data) {
                console.log(data);
                let html = '<a class="chat-'+data.sender.id+' dropdown-item container" href="/message/'+data.sender.name+'">'+
                    '<div class="row">'+
                        '<div class="notificationImage col-2">'+
                            '<img class="notificationImage" src="/img/profile-pictures/'+data.sender.picture+'" alt="" srcset="">'+
                        '</div>'+
                        '<div class="notificationDesc col-8">'+
                            '<div class="col-12 font-weight-bold">'+data.sender.name+'</div>'+
                            '<div class="col-12">'+data.message+'</div>'+
                        '</div>'+
                    '</div>'+
                '</a>';
                if($('a.chat-'+data.sender.id).length) {
                    $('a.chat-4').remove();
                    $('.chatNotifications').prepend(html);
                }else{
                    newMessages++;
                    $('.chatNotificationsCount').html(newMessages);
                    $('.chatNotifications').prepend(html); 
                }   
            }
        </script>
            {!! talk_live(['user'=>["id"=>auth()->user()->id, 'callback'=>['newmsg']]]) !!}
    @endpush
@endauth    