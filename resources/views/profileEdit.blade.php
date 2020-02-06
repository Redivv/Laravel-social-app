@extends('layouts.app')

@section('titleTag')
    <title>
        {{__('app.profileEditTitle')}}
    </title>
@endsection

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
                <legend>{{__('profile.picture')}}</legend>
                <label for="profilePictureInput" class="col-12 col-form-label">
                    <img src="{{asset('img/profile-pictures/'.$user->picture)}}" alt="{{__("profile.photo", ['user' => $user->name])}}">
                    @if ($user->pending_picture)
                        <div class="alert alert-primary mt-2" role="alert">
                            {{__('profile.pictureInfo')}}
                        </div>
                    @endif
                </label>
                <input type="file" class="form-control-file" name="profilePicture" id="profilePictureInput">
            </fieldset>

            <fieldset class="form-group col-12 row profileCity">
                <legend class="m-0">
                    <label for="profileCityInput" class="col-12 col-form-label">
                        {{__('profile.city')}}
                    </label>
                </legend>
                <input type="text" class="form-control" name="profileCity" id="profileCityInput" value="@if($user->city_id){{$user->city->name}}@endif">
            </fieldset>

            <fieldset class="form-group col-12 row profileRelationship">
                <legend class="m-0">
                    <label for="profileRelationshipInput" class="col-12 col-form-label">
                        {{__('profile.relationStatus')}}
                    </label>
                </legend>
                <div class="form-check form-check-inline col row">
                    <input class="form-check-input col-12" type="radio" name="profileRelationship" id="profileRelationshipInput1" value="0" @if($user->relationship_status === 0) checked @endif >
                    <label class="form-check-label col-12" for="profileRelationshipInput1">{{__('profile.status_free')}}</label>
                </div>
                <div class="form-check form-check-inline col row">
                    <input class="form-check-input col-12" type="radio" name="profileRelationship" id="profileRelationshipInput2" value="1" @if($user->relationship_status === 1 || $user->relationship_status === 4) checked @endif>
                    <label class="form-check-label col-12" for="profileRelationshipInput2">{{__('profile.status_taken')}}</label>
                </div>
                <div class="form-check form-check-inline col row">
                    <input class="form-check-input col-12" type="radio" name="profileRelationship" id="profileRelationshipInput3" value="2" @if($user->relationship_status === null) checked @endif>
                    <label class="form-check-label col-12" for="profileRelationshipInput3">{{__('profile.status_hidden')}}</label>
                </div>

                
                <span class="col-12 tagPartner"><i class="fas fa-user-tag" data-toggle="modal" data-target="#tagPartnerModal" data-tool="tooltip" title="{{__('profile.tagPartner')}}" data-placement="bottom"></i></span>

                <div id="userPartner" class="col-12">
                    @if($user->relationship_status == 4)
                        <div class="alert alert-primary mt-2" role="alert">
                            {{__('profile.partnerInfo')}}
                        </div>
                    @elseif($user->partner_id)
                        <div class="userFriend col">
                            <div class="userFriendContainer">
                                <i id="deletePartner" class="fa fa-times"></i>
                                <img src="{{asset('img/profile-pictures/'.$user->partner->picture)}}" alt="{{__('profile.photo', ['user' => $user->partner->name])}}">
                                <span>{{$user->partner->name}}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </fieldset>

            <fieldset class="form-group col-12 row profileDesc">
                <legend class="m-0">
                    <label for="profileDescInput" class="col-12 col-form-label">
                        {{__('profile.desc')}}
                    </label>
                </legend>
                <textarea type="text" class="form-control" name="profileDesc" id="profileDescInput" rows="4">
                    {{$user->description}}
                </textarea>
                <hr style="width:100%">
                <a class="btn form-btn mx-auto" href="{{url('password/reset')}}">{{__('profile.changePass')}}</a>
            </fieldset>

            <fieldset class="form-group col-12 row profileTags">
                <legend class="m-0">
                    <label for="profileTagsInput" class="col-12 col-form-label">
                        {{__('profile.hobby')}}
                    </label>
                </legend>
                <input type="text" class="form-control" name="profileTags[]" id="profileTagsInput" placeholder="Wpisz zainteresowanie i zatwierdź enterem">
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
                <button type="submit" class="btn">{{__('profile.save')}}</button>
            </div>
        </form>
    </div>

    @include('partials.profile.tagPartnerModal')
@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset("jqueryUi\jquery-ui.min.css")}}">
@endpush
    
@push('scripts')
    <script src="{{asset("jqueryUi\jquery-ui.min.js")}}"></script>
    <script src="{{asset('js/emoji.js')}}"></script>

    <script defer>
        var delete_msg          = "{{__('profile.deleteTag')}}";
        var base_url            = "{{url('/')}}";
        var deletePartnerMsgs   = "{{__('profile.deletePartnerMsgs')}}";

        $('#deletePartner').on('click',function() {
            if(confirm(deletePartnerMsgs)){
                html = '<input type="hidden" name="deletePartner" value="true">';
                $('#deletePartner').parents('.userFriend').replaceWith(html);
            } 
        });

        
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

    

    $('#friendsSearch').on('submit',function(e) {
        e.preventDefault();
        search(this);
    });


    function deleteTag(selected) {
        if (confirm(delete_msg)) {
            $(selected).remove();
            $('.tooltip:first').remove();
        }
    }

    function search(form) {
        let searchCriteria = $('#friendsSearch-input').val().trim();

        if (searchCriteria != "") {

            let url = baseUrl + "/user/profile/ajax/searchFriends";
                
            let spinnerHtml = '<div id="spinner" class="ajaxSpinner m-auto">'+
                '<div class="spinner-border text-dark" role="status">'+
                    '<span class="sr-only">Loading...</span>'+
                '</div>'+
            '</div>';

            $('#friends-searchOut').html(spinnerHtml);

            var request = $.ajax({
                method : 'get',
                url: url,
                data: {criteria:searchCriteria}
            });
            
            
            request.done(function(response){
                if (response.status === 'success') {
                    $('#friends-searchOut').html(response.html);

                    $('.userFriend>div').off('click');
                    $(".userFriend>div").on('click',function() {
                        $('.userFriend>div').removeClass('selected');
                        $(this).addClass('selected');
                    });

                    $('#tagPartnerButton').off('click');
                    $('#tagPartnerButton').one('click',function() {
                        $('#tagPartnerModal').modal('hide');

                        let selectedFriend = $('.userFriendContainer.selected').parent();

                        $('#userPartner').html("");

                        $(selectedFriend).prependTo('#userPartner');

                        $('#friends-searchOut').html("");

                        let html ='<i id="deletePartner" class="fa fa-times"></i>';

                        $('.userFriendContainer.selected').prepend(html);

                        $('#deletePartner').on('click',function() {
                           if(confirm(deletePartnerMsgs)){
                               html = '<input type="hidden" name="deletePartner" value="true">';
                               $('#deletePartner').parents('.userFriend').replaceWith(html);
                           } 
                        });
                    });
                }
            });
            
            
            request.fail(function (xhr){
                alert(xhr.responseJSON.message);
                $('#friends-searchOut').html("");
            });
        }
    }

    </script>
    
@endpush