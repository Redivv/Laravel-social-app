import "lightbox2";

import {
    addNewTagInputFromIn,
    addOnClickDeleteEventOnRemove,
    sendAjaxRequestToWithFormData,
    getDataByAjaxFromUrlWithData,
    showSpinnerOverlay
} from "./cultureFunctions";

$(document).ready(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('[data-tool="tooltip"]').tooltip();
    main();
});


function main() {

    addOnClickDeleteEventOnRemove(".itemTag");

    $('input[type=radio][name=options]').click(function(){
        $('.sortOptionBtn').removeClass('active');
        $(this).parent().addClass('active');
    });

    $('.cultureLikeBtn').on('click',function() {
        likeItem(this);
    });
    
    $('#searchTags').on('keydown',function(key){
        if (key.which == 13 || key.keyCode == 13) {
            key.preventDefault();
            addNewTagInputFromIn(this,'#searchTags-out');
        }
    });

    $('.cultureSection').on('click',function(e) {
        e.preventDefault();
        
        let catName = $(this).data('category');
        if (catName == "all") {
            $('#searchCategory-data').remove();
            $("#cultureSearch").submit();
        }
        let html = '<input id="searchCategory-data" type="hidden" name="searchCategory" value="'+catName+'">';

        if ($('#searchCategory-data').length) {
            $('#searchCategory-data').replaceWith(html);
        }else{
            $("#cultureSearch").prepend(html);
        }
        $("#cultureSearch").submit();
    });

    $('#reviewModal').one('show.bs.modal',function(e) {
       let itemId = $(e.relatedTarget).data('itemid');
       getDataByAjaxFromUrlWithData(baseUrl+"/culture/ajax/getReview",itemId);
    });

    $("#searchTags").autocomplete({
 
        source: function(request, response) {
            $.ajax({
                url: baseUrl+"/ajax/tag/autocompleteHobby",
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
}


function likeItem(selected) {
    let itemId = $(selected).data('id');
    let url = base_url+"/culture/ajax/likeItem";

    let currentAmount = $(selected).find('.likesCount').html();
    console.log(currentAmount);
    
    if ($(selected).hasClass('active')) {

        $(selected).removeClass('active');
        $(selected).find('.likesCount').html(parseInt(currentAmount)-1);
        if (currentAmount == 1) {
            $(selected).find('.likesCount').addClass('invisible');
        }
    }else{
        
        $(selected).addClass('active');
        $(selected).find('.likesCount').html(parseInt(currentAmount)+1);
        if (currentAmount == 0) {
            $(selected).find('.likesCount').removeClass('invisible');
        }
    }

    var request = $.ajax({
        method : 'post',
        url: url,
        data: {"_method": "patch", itemId:itemId}
    });
    
    
    request.fail(function (xhr){
        alert(xhr.responseJSON.message);
        $('.deleteItem').on('submit',function(e) {
            e.preventDefault();
            if (confirm(confirmMsg)) {
                showSpinnerOverlay();
                sendAjaxRequestToWithFormData(baseUrl+"/culture/deleteItem",this);
                $(this).parents('.resultBox').remove();
                $(".tooltip:first").remove();
            }
        });
    });
}