@extends('layouts.app')

@section('titleTag')
<title>
    {{__('app.adminDashboard')}}
</title>
@endsection

@push('styles')
<link rel="stylesheet" href="{{asset('css/adminPane.css')}}">
@endpush

@section('content')<div class="spinnerOverlay d-none">
    <div class="spinner-border text-warning" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>
<div class="darkOverlay d-none"></div>

<div id="adminCulturePane" class="container-fluid">
    <div class="adminLinks row">
        <a id="homeAdminLink" class="btn col-md-4 col-sm-12" href="{{route('adminHome')}}">
            {{__('admin.home')}}
        </a>
        <a id="cultureAdminLink" class="btn col-md-4 col-sm-12" href="{{route('adminCulture')}}">
            {{__('app.culture')}}
        </a>
    </div>
    
    <div class="row">
        <div class="tabsPills col-md-4">
            <div class="nav flex-column nav-pills" id="pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link tab" id="cultureCategories" data-toggle="pill" href="#cultureCategories-content" role="tab"
                    aria-controls="cultureCategories" aria-selected="true">
                    {{__('admin.cultureAllCategories')}} <span id="cultureCategoriesCount" class="ticketCount">5</span>
                </a>
                <a class="nav-link tab" id="cultureItems" data-toggle="pill" href="#cultureItems-content" role="tab"
                    aria-controls="cultureItems" aria-selected="true">
                    {{__('admin.cultureAllItems')}} <span id="cultureItemsCount" class="ticketCount">10</span>
                </a>
                <hr>
                <a class="nav-link creatingTab" id="cultureNewCategory" data-toggle="pill" href="#cultureNewCategory-content" role="tab"
                    aria-controls="cultureNewCategory" aria-selected="true">
                    {{__('admin.cultureAddCategory')}}
                </a>
                <a class="nav-link creatingTab" id="cultureNewItem" data-toggle="pill" href="#cultureNewItem-content" role="tab"
                    aria-controls="cultureNewItem" aria-selected="true">
                    {{__('admin.cultureAddItem')}}
                </a>
            </div>
        </div>
        <div class="tabsContent pt-2 pb-2 overflow-auto col-md-8 col-sm-12">
            <div class="tab-content" id="tabContent">
                <div class="tab-pane" id="cultureCategories-content" role="tabpanel" aria-labelledby="cultureCategories-tab"></div>
                <div class="tab-pane" id="cultureItems-content" role="tabpanel" aria-labelledby="cultureItems-tab"></div>
                <div class="tab-pane" id="cultureNewCategory-content" role="tabpanel" aria-labelledby="cultureNewCategory-tab">
                    @include('partials.admin.culture.newCategoryForm')
                </div>
                <div class="tab-pane" id="cultureNewItem-content" role="tabpanel" aria-labelledby="cultureNewItem-tab">
                    @include('partials.admin.culture.newItemForm')
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
@endpush    

@push('scripts')
<script>
    var __baseUrl = "{{url('/')}}";
</script>

<script src="{{asset('js/emoji.js')}}"></script>
<script src="{{asset('js/admin.js')}}"></script>

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