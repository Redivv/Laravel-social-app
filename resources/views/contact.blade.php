@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        @if (session()->has('message'))
            <div class="alert alert-success mt-3" role="alert">
                {{session()->get('message')}}
            </div>
        @endif

        <form method="post" class="mt-4" id="contactForm">
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
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="{{asset('js/emoji.js')}}"></script>

    <script>
        $('#EmailContent').emojioneArea({
        pickerPosition: "bottom",
        placeholder: "{{__('contact.emailContentPlaceholder')}}",
    });
    </script>
@endpush