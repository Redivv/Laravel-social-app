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
    addNewPartnerInput,
    deleteTargetElement
} from "./cultureFunctions";

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

    addOnClickDeleteEventOnRemove('#itemTags-out>.itemTag');

    $('.partnerDelete').on('click',function() {
        deleteTargetElement($(this).parents('.partner'));
    });

    $('.partnerThumb-input').on('change',function (evt) {
        let containerId = $(this).prev().attr('id');
        displayAddedImageIn(this,evt,'#'+containerId);
    });

    $('#resetImages.resetPicture').on('click', function () {
        clearImageInputTagAndPreviewContainer($('#itemImages'), '#itemImages-out');
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

    $('.categoryAttrAppend>i').on('click',addNewAttrForm);

    $('#newCategoryForm').on('submit',function (e) {
        e.preventDefault();
        
        let categoryNameIsFilled    = $('#categoryName').val().trim()   !== "";
        let firstAttributeIsFilled  = $('#categoryAttr1').val().trim()  !== "";

        if (categoryNameIsFilled && firstAttributeIsFilled) {
            showSpinnerOverlay();
            sendAjaxRequestToWithFormData(baseUrl+"/culture/newCategory",this);
        }else{
            alert(emptyFieldsMsg);
        }
    });

    $('#newItemForm').on('submit',function(e) {
        e.preventDefault();

        let categoryIsSelected      = $('#itemCategory option:selected').val().trim()   != 0;
        let itemNameIsFilled        = $('#itemName').val().trim()  !== "";
        let itemDescIsFilled        = $('#itemDesc').val().trim()  !== "";

        if (categoryIsSelected && itemNameIsFilled && itemDescIsFilled) {

            showSpinnerOverlay();
            sendAjaxRequestToWithFormData(baseUrl+"/culture/newItem",this);
        }else{
            alert(emptyFieldsMsg);
        }
    });

    $('#partnersForm').on('submit',function(e) {
        e.preventDefault();

        showSpinnerOverlay();
        sendAjaxRequestToWithFormData(baseUrl+"/admin/ajax/newPartners",this);

    });

    $('.categoryAttrDelete>i:last').on('click',function() {
        deleteAttrForm(this);
    });

    $('select#itemCategory').on('change',displayCategoryAttrs);

    $('#itemTags').on('keydown',function(key){
        if (key.which == 13 || key.keyCode == 13) {
            key.preventDefault();
            addNewTagInputFromIn(this);
        }
    });

    $('#addTagBtn').on('click',function() {
        addNewTagInputFromIn($('#itemTags'));
    });

    $('#itemImages').change(function(evt){
        displayAddedImageIn(this,evt,'#itemImages-out');
    });

    $('#itemThumbnail').change(function(evt){
        displayAddedImageIn(this,evt,'#itemThumbnail-out');
    });
    
    $( "#itemTags" ).autocomplete({
 
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

    let reviewCode = $('#itemReview').html();
    $('#itemReview').summernote({
        code:reviewCode,
        minHeight: 150  
    });

    $('.newPartnerBox>i').on('click',addNewPartnerInput);
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
