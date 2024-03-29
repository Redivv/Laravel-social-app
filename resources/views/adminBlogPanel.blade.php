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

<div id="adminCulturePane" class="container-fluid">
    <div class="adminLinks row">
        <a id="homeAdminLink" class="btn col-md-3 col-sm-12" href="{{route('adminHome')}}">
            {{__('admin.home')}}
        </a>
        <a id="cultureAdminLink" class="btn col-md-3 col-sm-12" href="{{route('adminCulture')}}">
            {{__('app.culture')}} & {{__('app.partners')}}
        </a>
        <a id="blogAdminLink" class="btn col-md-3 col-sm-12" href="{{route('adminBlog')}}">
            {{__('app.blog')}}
        </a>
    </div>
    
    <div class="row">
        <div class="tabsPills col-md-4">
            <div class="nav flex-column nav-pills" id="pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link tab" id="blogCategories" data-toggle="pill" href="#blogCategories-content" role="tab" aria-controls="blogCategories" aria-selected="true">
                    {{__('admin.cultureAllCategories')}}
                </a>
                <a class="nav-link tab" id="blogEvents" data-toggle="pill" href="#blogEvents-content" role="tab" aria-controls="blogEvents" aria-selected="true">
                    {{__('admin.cultureAllEvents')}}
                </a>
                <hr>
                <a class="nav-link creatingTab @if($elementType === "post") active @endif" id="blogNewPost" data-toggle="pill" href="#blogNewPost-content" role="tab" aria-controls="blogNewPost" aria-selected="true">
                    @if($elementType === "post")
                        {{__('admin.blogEditPost')}}
                    @else
                        {{__('admin.blogAddPost')}}
                    @endif
                </a>
                <a class="nav-link creatingTab @if($elementType === "event") active @endif" id="blogNewEvent" data-toggle="pill" href="#blogNewEvent-content" role="tab" aria-controls="blogNewEvent" aria-selected="true">
                    @if($elementType === "event")
                        {{__('admin.blogEditEvent')}}
                    @else
                        {{__('admin.blogAddEvent')}}
                    @endif
                </a>
            </div>
        </div>
        <div class="tabsContent pt-2 pb-2 overflow-auto col-md-8 col-sm-12">
            <div class="tab-content" id="tabContent">
                <div class="tab-pane" id="blogCategories-content" role="tabpanel" aria-labelledby="blogCategories-tab"></div>
                <div class="tab-pane" id="blogEvents-content" role="tabpanel" aria-labelledby="blogEvents-tab"></div>
                <div class="tab-pane @if($elementType === "post") active @endif" id="blogNewPost-content" role="tabpanel" aria-labelledby="blogNewPost-tab">
                    @include('partials.admin.blog.newPostForm')
                </div>
                <div class="tab-pane @if($elementType === "event") active @endif" id="blogNewEvent-content" role="tabpanel" aria-labelledby="blogNewEvent-tab">
                    @include('partials.admin.blog.newEventForm')
                </div>
            </div>
        </div>
    </div>
</div>

<span id="showTabsMenu"><i class="fas fa-arrow-left"></i></span>
@endsection



@push('styles')
    <style>
        #blogAdminLink{
            background-color: #f22103;
        }
    </style>
    <link rel="stylesheet" href="{{asset("jqueryUi\jquery-ui.min.css")}}">
@endpush    

@push('scripts')
<script>
    var __baseUrl           =  "{{url('/')}}";
    var savedChanges        =  "{{__('profile.savedChanges')}}";
    var deleteAttrMsg       =  "{{__('admin.deleteAttrMsg')}}";
    var emptyFieldsMsg      =  "{{__('admin.emptyFields')}}"
    var confirmMsg          =  "{{__('admin.confirmMsg')}}";
    var selectCategoryMsg   =  "{{__('admin.selectCategory')}}";
    var deleteHobby         =  "{{__('activityWall.deleteTags')}}";
    var resetImgMsg         =  "{{__('activityWall.resetPictures')}}";
    var deleteImages        =  "{{__('activityWall.deleteImages')}}";
    var badFileType         =  "{{__('chat.badFileType')}}";
    var deleteMsg           =  "{{__('admin.delete')}}";
</script>

<script src="{{asset("jqueryUi\jquery-ui.min.js")}}"></script>
<script src="{{asset('js/adminBlog.js')}}"></script>

<script defer>

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