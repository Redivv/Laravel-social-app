@extends('layouts.app')

@section('titleTag')
<title>
    Safo | {{__('app.adminDashboard')}}
</title>
@endsection

@push('styles')
<link rel="stylesheet" href="{{asset('css/adminPane.css')}}">
@endpush


@section('content')
<div class="spinnerOverlay d-none">
    <div class="spinner-border text-warning" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>
<div class="darkOverlay d-none"></div>

<div id="adminPane" class="container-fluid">
    <div class="adminLinks row">
        <a id="homeAdminLink" class="btn col-md-3 col-sm-12" href="{{route('adminHome')}}">
            {{__('admin.home')}}
        </a>
        <a id="cultureAdminLink" class="btn col-md-3 col-sm-12" href="{{route('adminCulture')}}">
            {{__('app.culture')}} & {{__('app.partners')}}
        </a>
        <a id="cultureAdminLink" class="btn col-md-3 col-sm-12" href="{{route('adminBlog')}}">
            {{__('app.blog')}}
        </a>
    </div>
    <div class="row">
        <div class="tabsPills col-md-4">
            <div class="nav flex-column nav-pills" id="pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link tab" id="profileTicket" data-toggle="pill" href="#profileTicket-content" role="tab"
                    aria-controls="profileTicket" aria-selected="true">
                    {{__('admin.profileTicket')}} <span id="profileTicketCount" class="ticketCount">@if($pictureTickets
                        != 0){{$pictureTickets}}@endif</span>
                </a>
                <a class="nav-link tab" id="userTicket" data-toggle="pill" href="#userTicket-content" role="tab"
                    aria-controls="userTicket" aria-selected="true">
                    {{__('admin.userTicket')}} <span id="userTicketCount" class="ticketCount">@if($userTickets !=
                        0){{$userTickets}}@endif</span>
                </a>
                <a class="nav-link tab" id="userList" data-toggle="pill" href="#userList-content" role="tab"
                    aria-controls="userList" aria-selected="true">{{__('admin.userList')}}</a>
                <a class="nav-link tab" id="tagList" data-toggle="pill" href="#tagList-content" role="tab"
                    aria-controls="tagList" aria-selected="true">{{__('admin.tagList')}}</a>
                <a class="nav-link tab" id="cityList" data-toggle="pill" href="#cityList-content" role="tab"
                    aria-controls="cityList" aria-selected="true">{{__('admin.cityList')}}</a>
                <hr>
                <a class="nav-link creatingTab" id="adminWideInfo" data-toggle="pill" href="#adminWideInfo-content"
                    role="tab" aria-controls="adminWideInfo" aria-selected="true">
                    {{__('admin.adminWideInfo')}}
                </a>
            </div>
        </div>
        <div class="tabsContent pt-2 pb-2 overflow-auto col-md-8 col-sm-12">
            <div class="tab-content" id="tabContent">
                <div class="tab-pane" id="profileTicket-content" role="tabpanel" aria-labelledby="profileTicket-tab">
                </div>
                <div class="tab-pane" id="userTicket-content" role="tabpanel" aria-labelledby="userTicket-tab"></div>
                <div class="tab-pane" id="userList-content" role="tabpanel" aria-labelledby="userList-tab"></div>
                <div class="tab-pane" id="tagList-content" role="tabpanel" aria-labelledby="tagList-tab"></div>
                <div class="tab-pane" id="cityList-content" role="tabpanel" aria-labelledby="cityList-tab"></div>
                <div class="tab-pane" id="adminWideInfo-content" role="tabpanel" aria-labelledby="adminWideInfo-tab">
                    @include('partials.admin.wideInfoForm')</div>
            </div>
        </div>
    </div>
</div>

<span id="showTabsMenu"><i class="fas fa-arrow-left"></i></span>
@endsection

@push('styles')
    <style>
        #homeAdminLink{
            background-color: #f22103;
        }
    </style>
@endpush    

@push('scripts')
<script>
    var __baseUrl           = "{{url('/')}}";
    var confirmMsg          = "{{__('admin.confirmMsg')}}";
    var noNotifications     = "{{__('nav.noNotifications')}}";
    var resetImgMsg         = "{{__('activityWall.resetPictures')}}";

</script>

<script src="{{asset('js/emoji.js')}}"></script>
<script src="{{asset('js/admin.js')}}"></script>

<script defer>
    Echo.private('users.' + window.Laravel.user)
        .notification((notification) => {
            let currentAmount;
            switch (notification.type.replace(/\\/g, "/")) {
                case 'App/Notifications/NewProfilePicture':
                    currentAmount = $('#profileTicketCount').html().trim();
                    if (currentAmount == "") {
                        currentAmount = 0;
                    }
                    $('#profileTicketCount').html(parseInt(currentAmount) + 1);
                    $('#profileTicket-fetchBtn').addClass('new');
                    break;
                case 'App/Notifications/UserFlagged':
                    currentAmount = $('#userTicketCount').html().trim();
                    if (currentAmount == "") {
                        currentAmount = 0;
                    }
                    $('#userTicketCount').html(parseInt(currentAmount) + 1);
                    $('#userTicket-fetchBtn').addClass('new');
                    break;

                default:
                    break;
            }

        });

    Echo.join('online')
        .joining((user) => {
            axios.patch('/api/user/' + user.name + '/online', {
                api_token: user.api_token
            });
        })

        .leaving((user) => {
            axios.patch('/api/user/' + user.name + '/offline', {
                api_token: user.api_token
            });
        })

</script>
@endpush
