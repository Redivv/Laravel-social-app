@extends('layouts.profile')

@section('startform')
    <form action="{{route('ProfileUpdate')}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('patch')
@endsection


@section('name')
    {{__('profile.username')}}:
    <input id="name" class="form-control" name="name" type="text" value="{{ $user->name}}">
@endsection

@section('city')
    {{__('profile.city')}}:
    @if (!$user->city_id)
        <input id="city" class="form-control" name="city" type="text" value="">
    @else
        <input id="city" class="form-control" name="city" type="text" value="{{$user->city->name}}">
    @endif
    
@endsection

@section('email')
    {{__('profile.email')}}:
    {{ $user->email}}
    <br>

@endsection

@section('birth')
    {{__('profile.birth')}}:
    {{ $user->birth_year }}
    <br>
@endsection

@section('photo')
    {{__('profile.photo')}}:
    <input id="photo" style="width:100%;" name="photo" type="file" accept=".png,.jpg">
    <div class="col-md-8 foto_frame" style="margin:20px 0px;">
        <img class="foto" src="{{asset('img/profile-pictures/'.$user->picture)}}" alt="">
    </div>
@endsection

@section('desc')
    {{__('profile.desc')}}:
    <textarea name="description" class="form-control" id="description" cols="30" rows="10">{{ $user->description }}</textarea>
@endsection

@section('endform')
    <button type="submit" style="margin-left:30px !important;" class="form-btn btn m-1" >{{__('profile.submit')}}</button>
    <a href="{{route('ProfileView')}}" class="form-btn btn">{{__('profile.cancel')}}</a>
    </form>
    <hr>
@endsection
@section('tags-form')
    <form action="#" method="post" id="tagForm">
        @csrf
        <label for="newTag">{{__('profile.Tags')}}</label>
        <input class="form-control" type="text" name="tag" id="tagInput">
        <button type="submit" class="form-btn btn mt-2" >{{__('profile.addTag')}}</button>
    </form>
    <output class="tagList row mt-3">
        @include('partials.tagListEdit')
    </output>
@endsection

@section('relations')
    
    <input type="radio" name="relations" id="Rstatus0" value="0" <?php if($user->relationship_status == 0){echo('checked');} ?>><label for="status0">Wolna</label><br>
    <input type="radio" name="relations" id="Rstatus1" value="1" <?php if($user->relationship_status == 1){echo('checked');} ?>><label for="status1">W związku</label><br>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
@endpush
    
@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        var delete_msg = "{{__('profile.deleteTag')}}";
        var base_url = "{{url('/')}}";
    </script>
    <script src="{{asset('js/profile.js')}}"></script>
@endpush