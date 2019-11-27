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
                                @foreach ($notifications['user'] as $userNot)
                                    @switch($userNot->type)

                                        {{-- User Notification --}}
                                        @case('App\Notifications\UserNotification')
                                            <a class="dropdown-item container @if($userNot['read_at']){{'read'}}@endif" href="{{str_replace('-','/',$userNot->data['link'])}}" target="__blank">
                                                <div class="row">
                                                    <div class="notificationImageBox col-2">
                                                        <img class="notificationImage" src="{{asset('img/profile-pictures/'.$userNot->data['user_image'])}}">
                                                    </div>
                                                    <div class="notificationDesc col-10">
                                                        <div class="col-12 descTime">{{$userNot->created_at->diffForHumans()}}</div>
                                                        <div class="col-12 descBody">{{__('nav.userNot1')}} <span class="font-weight-bold">{{$userNot->data['user_name']}}</span> {{$userNot->data['message']}}</div>
                                                    </div>
                                                </div>
                                            </a>
                                            @break
                                        
                                        {{-- Admin Special Notification --}}
                                        @case('App\Notifications\NewAdminPost')
                                            <a class="dropdown-item container @if($userNot['read_at']){{'read'}}@endif" href="{{route('home').'/#post'.$userNot->data['postId']}}" target="__blank">
                                                <div class="row">
                                                    <div class="notificationImageBox col-2">
                                                        <img class="notificationImage" src="{{asset('img/profile-pictures/'.$userNot->data['author_image'])}}">
                                                    </div>
                                                    <div class="notificationDesc col-10">
                                                        <div class="col-12 descTime">{{$userNot->created_at->diffForHumans()}}</div>
                                                        <div class="col-12 descBody"><span class="font-weight-bold">{{__('nav.userNotAdmin')}}</span></div>
                                                    </div>
                                                </div>
                                            </a>
                                            @break
                                    @endswitch
                                @endforeach
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
                            @foreach($notifications['chat'] as $chatNot)
                            <a class="chat-{{$chatNot->thread->conversation_id}} dropdown-item container @if(($chatNot->thread->is_seen == 1) || ($chatNot->thread->user_id == auth()->id())){{'read'}}@endif" href="/message/{{$chatNot->withUser->name}}" target="__blank">
                                <div class="row">
                                    <div class="notificationImageBox col-2">
                                        <img class="notificationImage" src="{{asset('img/profile-pictures/'.$chatNot->withUser->picture)}}" alt="" srcset="">
                                    </div>
                                    <div class="notificationDesc col-10">
                                        <div class="col-12 ">{{$chatNot->withUser->name}}</div>
                                        <div class="col-12 descTime">{{$chatNot->thread->updated_at->diffForHumans()}}</div>
                                        <div class="col-12 descBody">@if($chatNot->thread->pictures)<i class="far fa-file-image"></i>@endif @if($chatNot->thread->user_id == auth()->id())<i class="fas fa-reply"></i>@endif {{substr($chatNot->thread->message, 0, 20)}} @if($chatNot->thread->is_seen)<i class="fa fa-check"></i>@endif</div>
                                    </div>
                                </div>
                            </a>
                            @endforeach
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

                                    {{-- Admin Tickets --}}
                                    @case('App\Notifications\NewProfilePicture')
                                        <a class="{{$sysNot['id']}} dropdown-item alert alert-info @if($sysNot['read_at']){{'read'}}@endif" href="/admin/home">
                                            <div class="systemNotificationDate">{{$sysNot['created_at']->diffForHumans()}}</div>
                                            {{__('nav.pictureTicket')}}
                                        </a>
                                        @break
                                    @case('App\Notifications\UserFlagged')
                                        <a class="{{$sysNot['id']}} dropdown-item alert alert-info @if($sysNot['read_at']){{'read'}}@endif" href="/admin/home">
                                            <div class="systemNotificationDate">{{$sysNot['created_at']->diffForHumans()}}</div>
                                            {{__('nav.userTicket')}}
                                        </a>
                                        @break

                                    {{-- System Notifications --}}
                                    @case('App\Notifications\SystemNotification')
                                        <a class="{{$sysNot['id']}} dropdown-item alert alert-{{$sysNot->data['color']}} @if($sysNot['read_at']){{'read'}}@endif" href="{{str_replace('-','/',$sysNot->data['link'])}}">
                                            <div class="systemNotificationDate">{{$sysNot['created_at']->diffForHumans()}}</div>
                                            {{$sysNot->data['message']}}
                                        </a>
                                        @break
                                    {{-- Admin Special Notifiaction --}}
                                    @case('App\Notifications\AdminWideInfo')
                                        <a class="dropdown-item alert alert-info @if($sysNot['read_at']){{'read'}}@endif" href="{{route('ProfileView')}}">
                                            <div class="systemNotificationDate">{{$sysNot['created_at']->diffForHumans()}}</div>
                                            <div class="adminWideHeader">{{__('nav.adminWideInfoHeader')}}</div>
                                            {{$sysNot->data['content']}}
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
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link">{{__('app.dashboard')}}</a>
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
                            <i class="far fa-smile"></i><span id="userNotDesc" class="badge userNotificationsCount badge-pill badge-warning">@if($notifications['userAmount'] > 0){{$notifications['userAmount']}}@endif</span>
                        </a>

                        {{-- User Notifications --}}
                        <div class="dropdown-menu userNotifications">
                            @if (count($notifications['user']) == 0)
                                <div class="text-center usNoNot">{{__('nav.noNotifications')}}</div>
                            @else
                                @foreach ($notifications['user'] as $userNot)
                                    @switch($userNot->type)

                                        {{-- User Notification --}}
                                        @case('App\Notifications\UserNotification')
                                            <a class="dropdown-item container @if($userNot['read_at']){{'read'}}@endif" href="{{str_replace('-','/',$userNot->data['link'])}}" target="__blank">
                                                <div class="row">
                                                    <div class="notificationImageBox col-2">
                                                        <img class="notificationImage" src="{{asset('img/profile-pictures/'.$userNot->data['user_image'])}}">
                                                    </div>
                                                    <div class="notificationDesc col-10">
                                                        <div class="col-12 descTime">{{$userNot->created_at->diffForHumans()}}</div>
                                                        <div class="col-12 descBody">{{__('nav.userNot1')}} <span class="font-weight-bold">{{$userNot->data['user_name']}}</span> {{$userNot->data['message']}}</div>
                                                    </div>
                                                </div>
                                            </a>
                                            @break
                                        
                                        {{-- Admin Special Notification --}}
                                        @case('App\Notifications\NewAdminPost')
                                            <a class="dropdown-item container @if($userNot['read_at']){{'read'}}@endif" href="{{route('home').'/#post'.$userNot->data['postId']}}" target="__blank">
                                                <div class="row">
                                                    <div class="notificationImageBox col-2">
                                                        <img class="notificationImage" src="{{asset('img/profile-pictures/'.$userNot->data['author_image'])}}">
                                                    </div>
                                                    <div class="notificationDesc col-10">
                                                        <div class="col-12 descTime">{{$userNot->created_at->diffForHumans()}}</div>
                                                        <div class="col-12 descBody"><span class="font-weight-bold">{{__('nav.userNotAdmin')}}</span></div>
                                                    </div>
                                                </div>
                                            </a>
                                            @break
                                    @endswitch
                                @endforeach
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
                                <a class="chat-{{$chatNot->thread->conversation_id}} dropdown-item container @if(($chatNot->thread->is_seen == 1) || ($chatNot->thread->user_id == auth()->id())){{'read'}}@endif" href="/message/{{$chatNot->withUser->name}}" target="__blank">
                                    <div class="row">
                                        <div class="notificationImageBox col-2">
                                            <img class="notificationImage" src="{{asset('img/profile-pictures/'.$chatNot->withUser->picture)}}" alt="" srcset="">
                                        </div>
                                        <div class="notificationDesc col-10">
                                            <div class="col-12 ">{{$chatNot->withUser->name}}</div>
                                            <div class="col-12 descTime">{{$chatNot->thread->updated_at->diffForHumans()}}</div>
                                            <div class="col-12 descBody">@if($chatNot->thread->pictures)<i class="far fa-file-image"></i>@endif @if($chatNot->thread->user_id == auth()->id())<i class="fas fa-reply"></i>@endif {{substr($chatNot->thread->message, 0, 20)}} @if($chatNot->thread->is_seen)<i class="fa fa-check"></i>@endif</div>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
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

                                        {{-- Admin Tickets --}}
                                        @case('App\Notifications\NewProfilePicture')
                                            <a class="{{$sysNot['id']}} dropdown-item alert alert-info @if($sysNot['read_at']){{'read'}}@endif" href="/admin/home">
                                                <div class="systemNotificationDate">{{$sysNot['created_at']->diffForHumans()}}</div>
                                                {{__('nav.pictureTicket')}}
                                            </a>
                                            @break
                                        @case('App\Notifications\UserFlagged')
                                            <a class="{{$sysNot['id']}} dropdown-item alert alert-info @if($sysNot['read_at']){{'read'}}@endif" href="/admin/home">
                                                <div class="systemNotificationDate">{{$sysNot['created_at']->diffForHumans()}}</div>
                                                {{__('nav.userTicket')}}
                                            </a>
                                            @break

                                        {{-- System Notifications --}}
                                        @case('App\Notifications\SystemNotification')
                                            <a class="{{$sysNot['id']}} dropdown-item alert alert-{{$sysNot->data['color']}} @if($sysNot['read_at']){{'read'}}@endif" href="{{str_replace('-','/',$sysNot->data['link'])}}">
                                                <div class="systemNotificationDate">{{$sysNot['created_at']->diffForHumans()}}</div>
                                                {{$sysNot->data['message']}}
                                            </a>
                                            @break
                                        {{-- Admin Special Notifiaction --}}
                                        @case('App\Notifications\AdminWideInfo')
                                            <a class="dropdown-item alert alert-info @if($sysNot['read_at']){{'read'}}@endif" href="{{route('ProfileView')}}">
                                                <div class="systemNotificationDate">{{$sysNot['created_at']->diffForHumans()}}</div>
                                                <div class="adminWideHeader">{{__('nav.adminWideInfoHeader')}}</div>
                                                {{$sysNot->data['content']}}
                                            </a>
                                            @break
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
                            <a href="{{ route('ProfileView') }}" class="dropdown-item">{{__('app.profile')}}</a>
                            
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('adminHome') }}" class="dropdown-item">{{__('app.adminDashboard')}}</a>
                            @endif

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();"
                            >
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

                switch (notification.type.replace(/\\/g,"/")) {

                    // User Notification 
                    case 'App/Notifications/UserNotification':
                        updateUserNotifications()
                        console.log(notification.link);
                        console.log(notification.link.replace('-','/'));
                        html = '<a class="dropdown-item container" href="'+notification.link.replace(/-/g,'/')+'" target="__blank">'+
                                    '<div class="row">'+
                                        '<div class="notificationImageBox col-2">'+
                                            '<img class="notificationImage" src="/img/profile-pictures/'+notification.user_image+'">'+
                                        '</div>'+
                                        '<div class="notificationDesc col-10">'+
                                            '<div class="col-12 descTime">{{__("nav.newSysNotTime")}}</div>'+
                                            '<div class="col-12 descBody">{{__("nav.userNot1")}} <span class="font-weight-bold">'+notification.user_name+'</span> '+notification.message+'</div>'+
                                        '</div>'+
                                   '</div>'+
                                '</a>';
                        $('.userNotifications').prepend(html);
                        break;

                    // Special Admin Notifications
                    case 'App/Notifications/NewAdminPost':
                        updateUserNotifications()
                        html = '<a class="dropdown-item container" href="/user/home/#post'+notification.postId+'" target="__blank">'+
                                    '<div class="row">'+
                                        '<div class="notificationImageBox col-2">'+
                                            '<img class="notificationImage" src="/img/profile-pictures/'+notification.author_image+'">'+
                                        '</div>'+
                                        '<div class="notificationDesc col-10">'+
                                            '<div class="col-12 descTime">{{__("nav.newSysNotTime")}}</div>'+
                                            '<div class="col-12 descBody"><span class="font-weight-bold">{{__("nav.userNotAdmin")}}</span></div>'+
                                        '</div>'+
                                   '</div>'+
                                '</a>';
                        $('.userNotifications').prepend(html);
                        break;

                    // System Notification
                    case 'App/Notifications/SystemNotification':
                        updateSystemNotifications();
                        html = '<a class="'+notification.id+' dropdown-item alert alert-'+notification.color+'" href="'+notification.link.replace(/-/g,'/')+'">'+
                                        '<div class="systemNotificationDate">{{__("nav.newSysNotTime")}}</div>'+
                                        notification.message+
                                '</a>';
                        $('.systemNotifications').prepend(html);
                        break;
                    
                    // Special Admin Notification
                    case 'App/Notifications/AdminWideInfo':
                        updateSystemNotifications();
                        html = '<a class="'+notification.id+' dropdown-item alert alert-info" href="/admin/home">'+
                                        '<div class="systemNotificationDate">{{__("nav.newSysNotTime")}}</div>'+
                                        '<div class="adminWideHeader">{{__("nav.adminWideInfoHeader")}}</div>'+
                                        notification.content+
                                '</a>';
                        $('.systemNotifications').prepend(html);
                        break;
                    case 'App/Notifications/NewProfilePicture':
                        updateSystemNotifications();
                        html = '<a class="'+notification.id+' dropdown-item alert alert-info" href="/admin/home">'+
                                        '<div class="systemNotificationDate">{{__("nav.newSysNotTime")}}</div>'+
                                        '{{__("nav.pictureTicket")}}'+
                                '</a>';
                        $('.systemNotifications').prepend(html);
                        break;
                    case 'App/Notifications/UserFlagged':
                        updateSystemNotifications();
                        html = '<a class="'+notification.id+' dropdown-item alert alert-info" href="/admin/home">'+
                                        '<div class="systemNotificationDate">{{__("nav.newSysNotTime")}}</div>'+
                                        '{{__("nav.userTicket")}}'+
                                '</a>';
                        $('.systemNotifications').prepend(html);
                        break;
                    
                }
        
            });

            Echo.private(`seen.` + window.Laravel.user)

                .listen('MessagesWereSeen', (e) => {
                    alert('kek');
                    $('.chat-'+e.conversation_id+'>.descBody').append('<i class="fas fa-reply"></i>');
                });

            function updateUserNotifications() {
                let currentAmountNot = $('#userNotDesc').text();
                let html;
                if (currentAmountNot === "") {
                    currentAmountNot = 0;
                    $('.userNotificationsCount').html(parseInt(currentAmountNot)+1);
                    if ($('.usNoNot').length) {
                        $('.usNoNot').remove();
                    }
                }
                
            }

            function updateSystemNotifications() {
                let currentAmountNot = $('#descSys').text();
                let html;
                if (currentAmountNot === "") {
                    currentAmountNot = 0;
                    $('.systemNotificationsCount').html(parseInt(currentAmountNot)+1);
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
            }
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
                html = '<a class="chat-'+data.conversation_id+' dropdown-item container" href="/message/'+data.sender.name+' target="__blank">'+
                    '<div class="row">'+
                        '<div class="notificationImageBox col-2">'+
                            '<img class="notificationImage" src="/img/profile-pictures/'+data.sender.picture+'" alt="" srcset="">'+
                        '</div>'+
                        '<div class="notificationDesc col-8">'+
                            '<div class="col-12 ">'+data.sender.name+'</div>'+
                            '<div class="col-12 descTime">'+data.humans_time+' {{__("nav.ago")}}</div>'+
                            '<div class="col-12">'+data.message.substring(0,20)+'</div>'+
                        '</div>'+
                    '</div>'+
                '</a>';
                if($('a.chat-'+data.conversation_id).length) {
                    if ($('a.chat-'+data.conversation_id).hasClass('read')) {
                        $('#desktopTalk').html(parseInt(newMessages)+1);
                    }
                    $('a.chat-'+data.conversation_id).remove();
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