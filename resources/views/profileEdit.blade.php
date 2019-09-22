@extends('layouts.profile')

@section('startform')
<form action="">
@endsection


@section('name')
Nazwa:
<input type="text" placeholder="{{ $user->name}}">
@endsection

@section('city')
Miasto:
<input type="text" placeholder="{{ $user->city}}">
@endsection

@section('email')
E-mail:
<input type="text" placeholder="{{ $user->email}}">
@endsection

@section('birth')
Data urodzenia:
<select id="birth_year" class="form-control @error('birth_year') is-invalid @enderror" name="birth_year" required>
        @for ($year = date("Y")-1; $year >= 1950; $year --)
            <option value="{{$year}}">{{$year}}</option>
        @endfor
    </select>
@endsection

@section('photo')
Zdjęcie:
<input type="file" placeholder="{{ $user->birth_year}}">
@endsection
@section('endform')

@section('desc')
<textarea name="description" id="description" cols="30" rows="10">{{ $user->description }}</textarea>
@endsection
</form>
@endsection