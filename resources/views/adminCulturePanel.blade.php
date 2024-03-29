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
                <a class="nav-link tab" id="cultureCategories" data-toggle="pill" href="#cultureCategories-content" role="tab"
                    aria-controls="cultureCategories" aria-selected="true">
                    {{__('admin.cultureAllCategories')}}
                </a>
                <hr>
                <a class="nav-link creatingTab @if($elementType === "category") active @endif" id="cultureNewCategory" data-toggle="pill" href="#cultureNewCategory-content" role="tab" aria-controls="cultureNewCategory" aria-selected="true">
                    @if($elementType === "category") 
                        {{__('admin.cultureEditCategory')}}
                    @else
                        {{__('admin.cultureAddCategory')}}
                    @endif
                </a>
                <a class="nav-link creatingTab @if($elementType === "item") active @endif" id="cultureNewItem" data-toggle="pill" href="#cultureNewItem-content" role="tab" aria-controls="cultureNewItem" aria-selected="true">
                    @if($elementType === "item")
                        {{__('admin.cultureEditItem')}}
                    @else
                        {{__('admin.cultureAddItem')}}
                    @endif
                </a>
                <a class="nav-link creatingTab" id="culturePartners" data-toggle="pill" href="#culturePartners-content" role="tab" aria-controls="culturePartners" aria-selected="true">
                    {{__('app.partners')}}
                </a>
            </div>
        </div>
        <div class="tabsContent pt-2 pb-2 overflow-auto col-md-8 col-sm-12">
            <div class="tab-content" id="tabContent">
                <div class="tab-pane" id="cultureCategories-content" role="tabpanel" aria-labelledby="cultureCategories-tab"></div>
                <div class="tab-pane @if($elementType === "category") active @endif" id="cultureNewCategory-content" role="tabpanel" aria-labelledby="cultureNewCategory-tab">
                    @include('partials.admin.culture.newCategoryForm')
                </div>
                <div class="tab-pane @if($elementType === "item") active @endif" id="cultureNewItem-content" role="tabpanel" aria-labelledby="cultureNewItem-tab">
                    @include('partials.admin.culture.newItemForm')
                </div>
                <div class="tab-pane" id="culturePartners-content" role="tabpanel" aria-labelledby="culturePartners-tab">
                    @include('partials.admin.culture.partnersForm')
                </div>
            </div>
        </div>
    </div>
</div>

<span id="showTabsMenu"><i class="fas fa-arrow-left"></i></span>
@endsection



@push('styles')
    <style>
        #cultureAdminLink{
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
<script src="{{asset('js/adminCulture.js')}}"></script>

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