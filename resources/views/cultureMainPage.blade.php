@extends('layouts.app')

@section('titleTag')
    <title>
        {{__('app.culture')}}
    </title>
@endsection

@section('content')
    <div class="container">
        <form id="cultureSearch" class="mx-auto">
            <div class="input-group">
                <input type="text" name="titleName" class="form-control" placeholder="{{__('culture.searchName')}}" aria-label="Title Name" aria-describedby="button-addon2">
                <div class="input-group-append">
                <button class="btn" type="button"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
        <section id="cultureSections">
            <header>
                <h3>
                    {{__('culture.sections')}}
                </h3>
            </header>
            <output id="sectionsOutput" class="row">
                <a class="cultureSection col-2" href="#">
                    <h4>
                        Filmy
                    </h4>
                </a>
                <a class="cultureSection col-2" href="#">
                    <h4>
                        Filmy
                    </h4>
                </a>
                <a class="cultureSection col-2" href="#">
                    <h4>
                        Filmy
                    </h4>
                </a>
            </output>
        </section>
    </div>
@endsection

@push('styles')
    <style>
        .navCulture > .nav-link{
            color: #f66103 !important;
        }
    </style>
    <link rel="stylesheet" href="{{asset("jqueryUi\jquery-ui.min.css")}}">
@endpush