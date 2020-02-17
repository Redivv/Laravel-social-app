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
</div>
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