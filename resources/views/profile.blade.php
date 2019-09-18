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
                    <input id="name" type="text" size="15" class="form-control" name="name" value="">
                </div>
            </div>
            <div class="row">
                    <label for="surname" class="col-md-4 col-form-label">{{ __('surname')  }}</label>
                </div>
                <div class="row form-group">
                    <div class="col-md-6">
                        <input id="surname" type="text" size="15" class="form-control" name="surname" value="">
                    </div>
                </div>
            </div>
            <button type="submit" class="">Submit</button>
        </form>
    </div>
@endsection