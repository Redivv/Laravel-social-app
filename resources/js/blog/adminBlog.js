import "lightbox2";

import {
    sendAjaxRequestToWithFormData,
    addNewAttrForm,
    showSpinnerOverlay,
    deleteAttrForm,
    displayCategoryAttrs,
    addNewTagInputFromIn,
    displayAddedImageIn,
    turnOnToolipsOn,
    addOnClickDeleteEventOnRemove,
    clearImageInputTagAndPreviewContainer,
    deleteTargetElement
} from "./blogFunctions";

var pagiTarget = {
};

var pagiCount = {
}

$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    turnOnToolipsOn('[data-tool=tooltip]');

    main();
})

function main() {

    addOnClickDeleteEventOnRemove('#postTags-out>.postTag');

    $('#resetImages.resetPicture').on('click', function () {
        clearImageInputTagAndPreviewContainer($('#postImages'), '#postImages-out');
    });

    $('a.tab').one('click', function () {
        renderContent(this);
    });

    $('#showTabsMenu').on('click', function () {
        if ($('.tabsPills').hasClass('show')) {
            $('.tabsPills').removeClass('show');
            $('.friendsList').removeClass('show');
            $(this).html('<i class="fas fa-arrow-left"></i>');
            setTimeout(function () {
                $('.darkOverlay').addClass('d-none');
            }, 900);
        } else {
            $('.tabsPills').addClass('show');
            $('.darkOverlay').removeClass('d-none');
            $(this).html('<i class="fas fa-times"></i>');

            $('.darkOverlay').one('click', function () {
                $('.tabsPills').removeClass('show');
                $('#showTabsMenu').html('<i class="fas fa-arrow-left"></i>');
                setTimeout(function () {
                    $('.darkOverlay').addClass('d-none');
                }, 900);
            });

        }
    });

    $('#newPostForm').on('submit',function(e) {
        e.preventDefault();

        let categoryIsSelected      = $('#postCategory').val().trim()  !== "";
        let postNameIsFilled        = $('#postName').val().trim()  !== "";
        let postDescIsFilled        = $('#postDesc').val().trim()  !== "";

        if (categoryIsSelected && postNameIsFilled && postDescIsFilled) {

            showSpinnerOverlay();
            sendAjaxRequestToWithFormData(baseUrl+"/blog/newPost",this);
        }else{
            alert(emptyFieldsMsg);
        }
    });

    $('#newEventForm').on('submit',function(e) {
        e.preventDefault();

        let eventNameIsFilled       = $('#eventName').val().trim()  !== "";
        let eventURLIsFilled        = $('#eventURL').val().trim()  !== "";

        if (eventNameIsFilled && eventURLIsFilled) {

            showSpinnerOverlay();
            sendAjaxRequestToWithFormData(baseUrl+"/blog/newEvent",this);
        }else{
            alert(emptyFieldsMsg);
        }
    });

    $('#postTags').on('keydown',function(key){
        if (key.which == 13 || key.keyCode == 13) {
            key.preventDefault();
            addNewTagInputFromIn(this);
        }
    });

    $('#addTagBtn').on('click',function() {
        addNewTagInputFromIn($('#postTags'));
    });

    $('#postThumbnail').change(function(evt){
        displayAddedImageIn(this,evt,'#postThumbnail-out');
    });
    
    $( "#postTags" ).autocomplete({
 
        source: function(request, response) {
            $.ajax({
                url: __baseUrl+"/ajax/tag/autocompleteHobby",
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
    
    $( "#postCategory" ).autocomplete({
 
        source: function(request, response) {
            $.ajax({
                url: __baseUrl+"/ajax/tag/autocompleteCategory",
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

    let reviewCode = $('#postDesc').html();
    $('#postDesc').summernote({
        code:reviewCode,
        minHeight: 150  
    });
}

function renderContent(selected) {
    let targetId = $(selected).attr('id');
    let url = __baseUrl + '/admin/ajax/tab';

    $(document).one("ajaxSend", function () {
        let html = ' <div class="spinner-border text-dark" role="status">' +
            '<span class="sr-only">Loading...</span>' +
            '</div>';
        $('#' + targetId + '-content').html(html);
    });

    var request = $.ajax({
        method: 'get',
        url: url,
        data: {
            target: targetId
        }
    });


    request.done(function (response) {
        if (response.status === 'success') {
            $('#' + targetId + '-content').html(response.html);

            $('button.listBtn').on('click', function (e) {
                e.preventDefault();
                if (confirm(confirmMsg)) {
                    carryList(this, targetId);
                }
            });

        }
    });


    request.fail(function (xhr) {
        $.each(xhr.responseJSON.errors,function(key,value) {
            alert(value);
        });
        $('#' + targetId + '-content').html('');
    });

}

function carryList(decided, target) {
    let decision = $(decided).attr('name');
    if (decision == 'edit') {
        var editValue = prompt("Nowa Nazwa");
    } else {
        var editValue = "";
        $(decided).siblings('input[name=elementType]').remove();
    }
    $('.spinnerOverlay').removeClass('d-none');
    let elementId = $(decided).parent().serialize();

    var request = $.ajax({
        method: 'post',
        url: __baseUrl + '/admin/ajax/list',
        data: {
            "_method": "PATCH",
            elementId: elementId,
            decision: decision,
            editValue: editValue,
            target: target
        }
    });

    request.done(function (response) {
        if (response.status === 'success') {
            switch (decision) {
                case 'delete':
                    $(decided).parent().parent().parent().remove();
                    $('.spinnerOverlay').addClass('d-none');
                    break;

                case 'edit':
                    $(decided).parent().parent().prev().prev().html(editValue);
                    $('.spinnerOverlay').addClass('d-none');
                default:
                    $(decided).addClass('alreadySent');
                    $('.spinnerOverlay').addClass('d-none');
                    break;
            }
        }
    });


    request.fail(function (xhr) {
        $.each(xhr.responseJSON.errors,function(key,value) {
            alert(value);
        });
        $('.spinnerOverlay').addClass('d-none');
    });
}
