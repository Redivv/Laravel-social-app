@extends('layouts.app')

@section('content')
    
    <div class="container card-body">
        @foreach ($users as $user)
            <li>
                {{$user->name}} 
                <br>
                {{$user->description}}
            </li>
        @endforeach
    </div>
@endsection