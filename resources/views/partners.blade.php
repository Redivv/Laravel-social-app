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
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- Partners Ad -->
        <ins class="adsbygoogle"
            style="display:block"
            data-ad-client="ca-pub-2738699172205892"
            data-ad-slot="8184037468"
            data-ad-format="auto"
            data-full-width-responsive="true"></ins>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>
@endsection

@push('styles')
<style>
    .navPartners > .nav-link{
        color: #f66103 !important;
    }
</style>
@endpush