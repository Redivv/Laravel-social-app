@extends('layouts.app')

@section('titleTag')
    <title>
        Safo | {{__('app.partners')}}
    </title>
@endsection

@section('content')
    <div class="container">
        <output id="partners-out" class="row">
            @if ($partners)
                @foreach ($partners as $partner)
                    <a class="col partner row" href="{{$partner->url}}" target="__blank">
                        <div class="partnerImg col-12">
                            <img src="{{asset("img/partner-pictures/".$partner->thumbnail)}}" alt="">
                        </div>
                        <div class="partnerName col-12">
                            <h3>
                                {{$partner->name}}
                            </h3>
                        </div>
                    </a>
                @endforeach
            @endif
        </output>
    </div>
@endsection

@push('styles')
<style>
    .navPartners > .nav-link{
        color: #f66103 !important;
    }
</style>
@endpush