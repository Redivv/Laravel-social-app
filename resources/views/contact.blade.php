@extends('layouts.app')

@section('titleTag')
    <title>
        Safo | {{__('app.contact')}}
    </title>
@endsection

@section('content')
    <div class="container-fluid">

        @if (session()->has('message'))
            <div class="alert alert-success mt-3" role="alert">
                {{session()->get('message')}}
            </div>
        @endif

        <form method="post" class="mt-4" id="contactForm" enctype="multipart/form-data">
            @csrf
            <legend>
                <h3>
                    {{__('contact.formLegend')}}
                </h3>
            </legend>
            <div class="form-group">
              <label for="EmailSubject"><h4>{{__('contact.emailSubject')}}</h4></label>
              <input required type="text" class="form-control" id="EmailSubject" name="EmailSubject" placeholder="{{__('contact.emailSubjectPlaceholder')}}">
            </div>
            <div class="form-group">
              <label for="EmailContent"><h4>{{__('contact.emailContent')}}</h4></label>
              <textarea required class="form-control" id="EmailContent" name="EmailContent" placeholder="{{__('contact.emailContentPlaceholder')}}" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="EmailAttachments"><h4>{{__('contact.emailAttachments')}}</h4></label>
                <input class="form-control-file" type="file" name="EmailAttachments[]" id="EmailAttachments" multiple accept="image/*">
            </div>
            <output id="EmailAttachmentsOut"></output>
            <div class="form-group row mt-2">
                <button class="btn contactSubmitButton" type="submit">{{__('contact.send')}}</button>
            </div>
        </form>

        @if (count($admins) > 0)
            <div class="container mb-3">
                <div class="card" id="administrators">
                    <div class="card-header">
                        {{__('contact.admins')}}
                    </div>
                    <div class="card-body row">
                        @foreach ($admins as $admin)
                            <div class="col">
                                <h5 class="card-title adminName">
                                    <a href="{{route('ProfileOtherView',['user' => $admin->name])}}" target="__blank">
                                        {{$admin->name}}
                                    </a>
                                </h5>
                                <a href="mailto:{{$admin->email}}" class="adminMail">
                                    {{$admin->email}}
                                </a>
                                <br>
                                <a href="{{route('message.read',['user' => $admin->name])}}" class="adminChat" target="__blank" data-tool="tooltip" data-placement="bottom" title="{{__('contact.chat')}}"> 
                                    <i class="far fa-comment-dots"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')

    <script>
        var badFileType             =  "{{__('chat.badFileType')}}";
        var deleteImages            =  "{{__('activityWall.deleteImages')}}";
        var resetImgMsg             =  "{{__('activityWall.resetPictures')}}";
        var descPlaceholder         =  "{{__('contact.emailContentPlaceholder')}}";
    </script>

    <script src="{{asset('js/emoji.js')}}"></script>
    <script src="{{asset('js/contact.js')}}"></script>
@endpush