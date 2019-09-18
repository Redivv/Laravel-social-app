@extends('layouts.app')

@section('content')
    
    <div class="container card-body">
        <form method="POST" action="">
            @method('PATCH')
            @csrf
            <div class="row">
                <label for="name" class="col-md-4 col-form-label">{{ __('name')  }}</label>
            </div>
            <div class="row form-group">
                <div class="col-md-6">
                    <input id="name" type="text" size="15" class="form-control" name="name" placeholder="{{ __('name') }}">
                </div>
            </div>

            <div class="row">
                    <label for="surname" class="col-md-4 col-form-label">{{ __('surname')  }}</label>
            </div>
            <div class="row form-group">
                <div class="col-md-6">
                    <input id="surname" type="text" size="15" class="form-control" name="surname" placeholder="{{ __('surname') }}">
                </div>
            </div>

            <div class="row">
                <label for="city" class="col-md-4 col-form-label">{{ __('city')  }}</label>
        </div>
        <div class="row form-group">
            <div class="col-md-6">
                <input id="city" type="text" size="15" class="form-control" name="city" placeholder="{{ __('city') }}">
            </div>
        </div>

            <div class="row">
                <label for="description" class="col-md-4 col-form-label">{{ __('description')  }}</label>
            </div>
            <div class="row form-group">
                <div class="col-md-6">
                    <textarea id="description" type="text" cols="5" rows="4" class="form-control" name="description" placeholder="Opis"></textarea>
                </div>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>
@endsection