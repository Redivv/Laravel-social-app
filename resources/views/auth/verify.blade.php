@extends('layouts.forms')

@section('titleTag')
    <title>
        {{__('app.verifyTitle')}}
    </title>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('registeration.verify') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('registeration.newMail') }}
                        </div>
                    @endif

                    {{ __('registeration.verificationInfo1') }}
                    {{ __('registeration.verificationInfo2') }}, <a href="{{ route('verification.resend') }}">{{ __('registeration.verificationInfo3') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('styles')
    <style>
        body{
            background-image: url("/images/background.jpg");
        }
    </style>
@endpush
