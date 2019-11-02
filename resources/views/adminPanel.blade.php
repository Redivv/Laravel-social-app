@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/adminPane.css')}}">
@endpush


@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="tabsPills col-md-4 col-sm-12">
            <div class="nav flex-column nav-pills" id="pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link tab" id="profileTicket" data-toggle="pill" href="#profileTicket-content" role="tab" aria-controls="profileTicket" aria-selected="true">{{__('admin.profileTicket')}}</a>
                <a class="nav-link tab" id="userTicket" data-toggle="pill" href="#userTicket-content" role="tab" aria-controls="userTicket" aria-selected="true">{{__('admin.userTicket')}}</a>
                <hr>
                <a class="nav-link tab" id="userList" data-toggle="pill" href="#userList-content" role="tab" aria-controls="userList" aria-selected="true">{{__('admin.userList')}}</a>
                <a class="nav-link tab" id="tagList" data-toggle="pill" href="#tagList-content" role="tab" aria-controls="tagList" aria-selected="true">{{__('admin.tagList')}}</a>
                <a class="nav-link tab" id="cityList" data-toggle="pill" href="#cityList-content" role="tab" aria-controls="cityList" aria-selected="true">{{__('admin.cityList')}}</a>
            </div>
        </div>
        <div class="tabsContent pt-2 pb-2 overflow-auto col-md-8 col-sm-12">
            <div class="tab-content" id="tabContent">
                <div class="tab-pane" id="profileTicket-content" role="tabpanel" aria-labelledby="profileTicket-tab"></div>
                <div class="tab-pane" id="userTicket-content" role="tabpanel" aria-labelledby="userTicket-tab"></div>

                <div class="tab-pane" id="userList-content" role="tabpanel" aria-labelledby="userList-tab"></div>
                <div class="tab-pane" id="tagList-content" role="tabpanel" aria-labelledby="tagList-tab"></div>
                <div class="tab-pane" id="cityList-content" role="tabpanel" aria-labelledby="cityList-tab"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        var __baseUrl = "{{url('/')}}";
    </script>

    <script src="{{asset('js/admin.js')}}"></script>
    
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
        </script>
@endpush