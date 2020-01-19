@extends('layouts.app')

@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success mt-2 mb-2 mx-auto text-center" style="width:50%" role="alert">
            {{session()->get('message')}}
        </div>
    @endif

    <div class="container">
        <form id="profileEditForm" action="{{route('ProfileUpdate')}}" class="row text-center" method="post" enctype="multipart/form-data">
            @csrf
            @method("patch")

            <fieldset class="form-group col-12 row profilePicture">
                <legend>Zdjęcie Profilowe</legend>
                <label for="profilePictureInput" class="col-12 col-form-label">
                    <img src="{{asset('img/profile-pictures/'.$user->picture)}}" alt="{{__("profile.photo", ['user' => $user->name])}}">
                    @if ($user->pending_picture)
                        <div class="alert alert-primary mt-2" role="alert">
                            Twoje Zdjęcie Profilowe Zostało Wysłane do Administracji w celu Akceptacji
                        </div>
                    @endif
                </label>
                <input type="file" name="profilePicture" id="profilePictureInput">
            </fieldset>

            <fieldset class="form-group col-12 row profileCity">
                <legend class="m-0">
                    <label for="profileCityInput" class="col-12 col-form-label">
                        Miasto
                    </label>
                </legend>
                <input type="text" class="form-control" name="profileCity" id="profileCityInput" value="{{$user->city->name}}">
            </fieldset>

            <fieldset class="form-group col-12 row profileRelationship">
                <legend class="m-0">
                    <label for="profileRelationshipInput" class="col-12 col-form-label">
                        Status Związku
                    </label>
                </legend>
                <div class="form-check form-check-inline col row">
                    <input class="form-check-input col-12" type="radio" name="profileRelationship" id="profileRelationshipInput1" value="0" @if($user->relationship_status === 0) checked @endif >
                    <label class="form-check-label col-12" for="profileRelationshipInput1">Wolna</label>
                </div>
                <div class="form-check form-check-inline col row">
                    <input class="form-check-input col-12" type="radio" name="profileRelationship" id="profileRelationshipInput2" value="1" @if($user->relationship_status === 1) checked @endif>
                    <label class="form-check-label col-12" for="profileRelationshipInput2">W związku</label>
                </div>
                <div class="form-check form-check-inline col row">
                    <input class="form-check-input col-12" type="radio" name="profileRelationship" id="profileRelationshipInput3" value="2" @if($user->relationship_status === null) checked @endif>
                    <label class="form-check-label col-12" for="profileRelationshipInput3">Nie Wyświetlaj</label>
                </div>
            </fieldset>

            <fieldset class="form-group col-12 row profileDesc">
                <legend class="m-0">
                    <label for="profileDescInput" class="col-12 col-form-label">
                        Opis
                    </label>
                </legend>
                <textarea type="text" class="form-control" name="profileDesc" id="profileDescInput" rows="4">
                    {{$user->description}}
                </textarea>
            </fieldset>

            <fieldset class="form-group col-12 row profileTags">
                <legend class="m-0">
                    <label for="profileTagsInput" class="col-12 col-form-label">
                        Zainteresowania
                    </label>
                </legend>
                <input type="text" class="form-control" name="profileTags" id="profileTagsInput" placeholder="Wpisz zainteresowanie i zatwierdź enterem">
                <output id="profileTagsOut" class="container row">
                    @foreach ($tags as $tag)
                        <div class="col-2 userTag" data-tool="tooltip" title="{{__('activityWall.deleteTags')}}" data-placement="bottom">
                            {{$tag}}
                            <input type="hidden" value="{{$tag}}" name="profileTags[]">
                        </div>
                    @endforeach
                </output>
            </fieldset>

            <div class="form-group col-12 row profileSave">
                <button type="submit" class="btn">Zapisz</button>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset("jqueryUi\jquery-ui.min.css")}}">
@endpush
    
@push('scripts')
    <script src="{{asset("jqueryUi\jquery-ui.min.js")}}"></script>
    <script src="{{asset('js/emoji.js')}}"></script>

    <script defer>
        var delete_msg = "{{__('profile.deleteTag')}}";
        var base_url = "{{url('/')}}";

        
        $('[data-tool="tooltip"]').tooltip();

        $('#profileDescInput').emojioneArea({
            pickerPosition: "top",
            placeholder: "\xa0",
        });

        $('.userTag').on('click',function(){
            deleteTag(this);
        });

        $('#profileTagsInput').on('keydown',function(e) {
            if (e.keyCode == 13 || e.which == 13) {
                e.preventDefault();
                let newTag = $('#profileTagsInput').val().trim();
                if (newTag != "") {
                    let html = '<div class="col-2 userTag" data-tool="tooltip" title="{{__('activityWall.deleteTags')}}" data-placement="bottom">'+
                            newTag+
                            '<input type="hidden" value="'+newTag+'" name="profileTags[]">'+
                        '</div>';
                        $('#profileTagsOut').append(html);
                        $('#profileTagsInput').val("");
                        $('.userTag:last').on('click',function(){
                            deleteTag(this);
                        });
                        $('.userTag:last').tooltip();    
                }
            } 
        });

        

    $("#profileTagsInput").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: base_url+"/ajax/tag/autocompleteHobby",
                data: {
                    term : request.term
                },
                dataType: "json",
                success: function(data){
                    var resp = $.map(data,function(obj){
                    return obj.name;
                }); 
                response(resp);
                }
            });
        },
        minLength: 1
    });


        function deleteTag(selected) {
            if (confirm(delete_msg)) {
                $(selected).remove();
                $('.tooltip:first').remove();
            }
        }

    </script>
    
@endpush