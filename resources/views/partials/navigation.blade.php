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

                    <a href="#" class="nav-link navNotifications" data-type="userNotifications" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="far fa-smile"></i><span class="badge userNotificationsCount badge-pill badge-warning">@if($notifications['userAmount'] > 0){{$notifications['userAmount']}}@endif</span>
                    </a>

                    {{-- User Notifications Mobile --}}
                    <div class="dropdown-menu pl-2 pr-2 position-absolute userNotifications">
                        @if (count($notifications['user']) == 0)
                            <div class="text-center usNoNot">{{__('nav.noNotifications')}}</div>
                        @else
                            Powiadomienia
                        @endif
                    </div>

                </li>

                <li class="nav-item pr-5 pl-5">

                    <a href="#" class="nav-link navNotifications" data-type="chatNotifications" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="far fa-comment"></i>
                        <span class="badge chatNotificationsCount badge-pill badge-warning">@if($notifications['chatAmount'] > 0){{$notifications['chatAmount']}}@endif</span>
                    </a>

                    {{-- Chat Notifications Mobile --}}
                    <div class="dropdown-menu pl-2 pr-2 position-absolute chatNotifications">
                        @if (count($notifications['chat']) == 0)
                            <div class="text-center chatNoNot">{{__('nav.noNotifications')}}</div>
                        @else
                            @foreach ($notifications['chat'] as $chatNot)
                                <a class="chat-{{$chatNot['data']['sender_id']}} dropdown-item container @if($chatNot['read_at']){{'read'}}@endif" href="/message/{{$chatNot['senderName']}}">
                                    <div class="row">
                                        <div class="notificationImage col-2">
                                            <img class="notificationImage" src="{{asset('img/profile-pictures/'.$chatNot['senderPicture'])}}" alt="" srcset="">
                                        </div>
                                        <div class="notificationDesc col-8">
                                            <div class="col-12 ">{{$chatNot['senderName']}}</div>
                                            <div class="col-12 descTime">{{$chatNot['created_at']}}</div>
                                            <div class="col-12">@if($chatNot['data']['image_present'])<i class="far fa-file-image"></i>@endif {{$chatNot['data']['message_body']}}</div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                            <div class="dropdown-divider"></div>
                            <a class="clearAllBtn" data-type="chatNoNot" href="#">{{__('nav.deleteAll')}}</a>
                        @endif
                    </div>

                </li>

                <li class="nav-item">

                    <a href="#" class="nav-link navNotifications" data-type="systemNotifications" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="far fa-user"></i><span class="badge systemNotificationsCount badge-pill badge-warning">@if($notifications['systemAmount'] > 0){{$notifications['systemAmount']}}@endif</span>
                    </a>

                    {{-- System Notifications Mobile --}}
                    <div class="dropdown-menu pl-2 pr-2 systemNotifications position-absolute p-2">
                        @if (count($notifications['system']) == 0)
                            <div class="text-center sysNoNot">{{__('nav.noNotifications')}}</div>
                        @else
                            @foreach ($notifications['system'] as $sysNot)
                                @switch($sysNot['type'])
                                    @case('App\Notifications\NewProfilePicture')
                                        <a class="{{$sysNot['id']}} dropdown-item alert alert-info @if($chatNot['read_at']){{'read'}}@endif" href="/admin/home">
                                            {{__('nav.pictureTicket')}}
                                        </a>
                                        @break
                                    @case('App\Notifications\UserFlagged')
                                        <a class="{{$sysNot['id']}} dropdown-item alert alert-info" href="/admin/home">
                                            {{__('nav.userTicket')}}
                                        </a>
                                        @break
                                    @case('App\Notifications\AcceptedPicture')
                                        <a class="dropdown-item alert alert-success" href="/profile">
                                            {{__('nav.pictureOk')}} 
                                        </a>
                                        @break
                                    @case('App\Notifications\DeniedPicture')
                                        <a class="dropdown-item alert alert-danger" href="/profile">
                                            {{__('nav.pictureDeny')}} 
                                        </a>
                                        @break
                                @endswitch
                            @endforeach
                            <div class="dropdown-divider"></div>
                            <a class="clearAllBtn" data-type="sysNoNot" href="#">{{__('nav.deleteAll')}}</a>
                        @endif
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
                        <a href="#" class="nav-link navNotifications" data-type="userNotifications" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="far fa-smile"></i><span class="badge userNotificationsCount badge-pill badge-warning">@if($notifications['userAmount'] > 0){{$notifications['userAmount']}}@endif</span>
                        </a>

                        {{-- User Notifications --}}
                        <div class="dropdown-menu userNotifications">
                            @if (count($notifications['user']) == 0)
                                <div class="text-center usNoNot">{{__('nav.noNotifications')}}</div>
                            @else
                                Powiadomienia
                            @endif
                        </div>

                    </li>

                    <li class="nav-item">

                        <a href="#" class="nav-link pr-4 pl-4 navNotifications" data-type="chatNotifications" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="far fa-comment"></i>
                            <span id="desktopTalk" class="badge chatNotificationsCount badge-pill badge-warning">@if($notifications['chatAmount'] > 0){{$notifications['chatAmount']}}@endif</span>
                        </a>
                        {{-- Chat Notifications --}}
                        <div class="dropdown-menu chatNotifications">
                            @if (count($notifications['chat']) == 0)
                                <div class="text-center chatNoNot">{{__('nav.noNotifications')}}</div>
                            @else
                                @foreach($notifications['chat'] as $chatNot)
                                <a class="chat-{{$chatNot['data']['sender_id']}} dropdown-item container @if($chatNot['read_at']){{'read'}}@endif" href="/message/{{$chatNot['senderName']}}">
                                    <div class="row">
                                        <div class="notificationImage col-2">
                                            <img class="notificationImage" src="{{asset('img/profile-pictures/'.$chatNot['senderPicture'])}}" alt="" srcset="">
                                        </div>
                                        <div class="notificationDesc col-10">
                                            <div class="col-12">{{$chatNot['senderName']}}</div>
                                            <div class="col-12 descTime">{{$chatNot['created_at']}}</div>
                                            <div class="col-12">@if($chatNot['data']['image_present'])<i class="far fa-file-image"></i>@endif {{$chatNot['data']['message_body']}}</div>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                                <div class="dropdown-divider"></div>
                                <a class="clearAllBtn" data-type="chatNoNot" href="#">{{__('nav.deleteAll')}}</a>
                            @endif
                        </div>

                    </li>

                    <li class="nav-item">

                        <a href="#" class="nav-link navNotifications" data-type="systemNotifications" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="far fa-user"></i><span id="descSys" class="badge systemNotificationsCount badge-pill badge-warning">@if($notifications['systemAmount'] > 0){{$notifications['systemAmount']}}@endif</span>
                        </a>

                        {{-- System Notifications --}}
                        <div class="dropdown-menu systemNotifications p-2">
                            @if (count($notifications['system']) == 0)
                                <div class="text-center sysNoNot">{{__('nav.noNotifications')}}</div>
                            @else
                                @foreach ($notifications['system'] as $sysNot)
                                    @switch($sysNot['type'])
                                        @case('App\Notifications\NewProfilePicture')
                                            <a class="{{$sysNot['id']}} dropdown-item alert alert-info @if($chatNot['read_at']){{'read'}}@endif" href="/admin/home">
                                                {{__('nav.pictureTicket')}}
                                            </a>
                                            @break
                                        @case('App\Notifications\UserFlagged')
                                            <a class="{{$sysNot['id']}} dropdown-item alert alert-info" href="/admin/home">
                                                {{__('nav.userTicket')}}
                                            </a>
                                            @break
                                        @case('App\Notifications\AcceptedPicture')
                                            <a class="dropdown-item alert alert-success" href="/profile">
                                                {{__('nav.pictureOk')}}
                                            </a>
                                            @break
                                        @case('App\Notifications\DeniedPicture')
                                            <a class="dropdown-item alert alert-danger" href="/profile">
                                                {{__('nav.pictureDeny')}}
                                            </a>
                                            @break
                                        @default
                                            
                                    @endswitch
                                @endforeach
                                <div class="dropdown-divider"></div>
                                <a class="clearAllBtn" data-type="sysNoNot">{{__('nav.deleteAll')}}</a>
                            @endif
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
            var baseUrl =   "{{url('/')}}";
            var noNotifications = "{{__('nav.noNotifications')}}";
        </script>
        <script src="{{asset('js/navigation.js')}}"></script>
        <script>
            Echo.private('users.'+window.Laravel.user)

            .notification((notification) => {
                let currentAmountNot = $('#descSys').text();
                let html
                if (currentAmountNot === "") {
                    currentAmountNot = 0;
                    if ($('.sysNoNot').length) {
                        html = '<div class="dropdown-divider"></div>'+
                                    '<a class="clearAllBtn">{{__("nav.deleteAll")}}</a>';
                        $('.sysNoNot').remove();
                        $('.systemNotifications').append(html);

                        $('a.clearAllBtn').one('click',function() {
                            let type= $(this).data('type');
                            let html = '<div class="text-center '+type+'">'+noNotifications+'</div>';
                            $(this).parent().html(html);

                            let url = baseUrl+'/user/deleteNotifications';
                            
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });

                            let request = $.ajax({
                                method : 'post',
                                url: url,
                                data: {"_method":"DELETE",type:type}
                            });
        
                            request.fail(function (xhr){
                                alert(xhr.responseJSON.message);
                            });
                        })
                    }
                }

                switch (notification.type.replace(/\\/g,"/")) {
                    case 'App/Notifications/NewProfilePicture':
                        html = '<a class="'+notification.id+' dropdown-item alert alert-info" href="/admin/home">'+
                                        '{{__("nav.pictureTicket")}}'+
                                '</a>';
                        break;
                    case 'App/Notifications/UserFlagged':
                        html = '<a class="'+notification.id+' dropdown-item alert alert-info" href="/admin/home">'+
                                        '{{__("nav.userTicket")}}'+
                                '</a>';
                        break;
                    case 'App/Notifications/AcceptedPicture':
                        html = '<a class="'+notification.id+' dropdown-item alert alert-success" href="/admin/home">'+
                                        '{{__("nav.pictureOk")}}'+
                                '</a>';
                        break;
                    case 'App/Notifications/DeniedPicture':
                        html = '<a class="'+notification.id+' dropdown-item alert alert-danger" href="/admin/home">'+
                                        '{{__("nav.pictureDeny")}}'+
                                '</a>';
                        break;
                    
                }
                $('.systemNotificationsCount').html(parseInt(currentAmountNot)+1);
                $('.systemNotifications').prepend(html);
        
            });
        </script>

        <script>
            var newmsg = function(data) {
                let newMessages = $('#desktopTalk').html();
                let html;
                if(newMessages.trim() == ""){
                    newMessages = 0;
                    if($('.chatNoNot').length){
                        $('.chatNoNot').remove();
                        html = '<div class="dropdown-divider"></div>'+
                                    '<a class="clearAllBtn">{{__("nav.deleteAll")}}</a>';
                        $('.chatNotifications').append(html);

                        $('a.clearAllBtn').one('click',function() {
                            let type= $(this).data('type');
                            let html = '<div class="text-center '+type+'">'+noNotifications+'</div>';
                            $(this).parent().html(html);

                            let url = baseUrl+'/user/deleteNotifications';
                            
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });

                            let request = $.ajax({
                                method : 'post',
                                url: url,
                                data: {"_method":"DELETE",type:type}
                            });
        
                            request.fail(function (xhr){
                                alert(xhr.responseJSON.message);
                            });
                        })
                    }
                }
                if (data.message == null || data.message.trim() == "") {
                    data.message = '<i class="far fa-file-image"></i>';
                }
                html = '<a class="chat-'+data.sender.id+' dropdown-item container" href="/message/'+data.sender.name+'">'+
                    '<div class="row">'+
                        '<div class="notificationImage col-2">'+
                            '<img class="notificationImage" src="/img/profile-pictures/'+data.sender.picture+'" alt="" srcset="">'+
                        '</div>'+
                        '<div class="notificationDesc col-8">'+
                            '<div class="col-12 ">'+data.sender.name+'</div>'+
                            '<div class="col-12 descTime">'+data.humans_time+' {{__("nav.ago")}}</div>'+
                            '<div class="col-12">'+data.message+'</div>'+
                        '</div>'+
                    '</div>'+
                '</a>';
                if($('a.chat-'+data.sender.id).length) {
                    if ($('a.chat-'+data.sender.id).hasClass('read')) {
                        $('#desktopTalk').html(parseInt(newMessages)+1);
                    }
                    $('a.chat-'+data.sender.id).remove();
                    $('.chatNotifications').prepend(html);
                }else{
                    newMessages++;
                    $('.chatNotificationsCount').html(newMessages);
                    $('.chatNotifications').prepend(html);
                    $('#desktopTalk').html(parseInt(newMessages)+1);
                }

            }
        </script>
            {!! talk_live(['user'=>["id"=>auth()->user()->id, 'callback'=>['newmsg']]]) !!}
    @endpush
@endauth    