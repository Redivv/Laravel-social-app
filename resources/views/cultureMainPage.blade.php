@extends('layouts.app')

@section('titleTag')
    <title>
        {{__('app.culture')}}
    </title>
@endsection

@section('content')
    <div class="container-fluid">
        <form id="cultureSearch" class="mx-auto">
            <div class="input-group">
                <input type="text" name="titleName" class="form-control" placeholder="{{__('culture.searchName')}}" aria-label="Title Name" aria-describedby="search Button">
                <div class="input-group-append">
                    <button class="btn" type="button"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div id="advancedSearch" class="form-group collapse row">
                <div class="input-group col-md-6 col-sm-12 tagSearch">
                    <input type="text" name="tagName" class="form-control" placeholder="{{__('culture.searchTags')}}" aria-label="Tag Name" aria-describedby="tag search button">
                    <div class="input-group-append">
                        <button class="btn" type="button">
                            {{__('searcher.add')}}
                        </button>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 sortOptions row">
                    <label class="col btn sortOptionBtn active">
                        <input type="radio" name="options" id="lettersSort" autocomplete="off" checked> Active
                    </label>
                    <label class="col btn sortOptionBtn">
                        <input type="radio" name="options" id="likesSort" autocomplete="off"> Radio
                    </label>
                    <label class="col btn sortOptionBtn">
                        <input type="radio" name="options" id="dateSort" autocomplete="off"> Radio
                    </label>
                </div>
            </div>
            <div class="togglerBox">
                <a class="advancedSearchToggle" data-toggle="collapse" href="#advancedSearch" role="button" aria-expanded="false" aria-controls="collapseExample">
                    <span class="toggleArrow"><i class="fas fa-sort-up"></i></span>
                    <div>{{__('culture.advancedSearchToggle')}}</div>
                </a>
            </div>
        </form>
        <section id="cultureSections">
            <header>
                <h3>
                    {{__('culture.sections')}}
                </h3>
            </header>
            <output id="sectionsOutput" class="row">
                <a class="cultureSection col" href="#">
                    <h4>
                        Gry
                    </h4>
                </a>
                <a class="cultureSection col" href="#">
                    <h4>
                        Filmy
                    </h4>
                </a>
                <a class="cultureSection col" href="#">
                    <h4>
                        Książki
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