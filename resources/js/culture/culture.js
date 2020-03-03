import {
    addNewTagInputFromIn,
    addOnClickDeleteEventOnRemove,
    sendAjaxRequestToWithFormData,
    showSpinnerOverlay
} from "./cultureFunctions";
$(document).ready(function(){
    main();
});


function main() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('[data-tool=tooltip]').tooltip();

    addOnClickDeleteEventOnRemove(".itemTag");

    $('input[type=radio][name=options]').click(function(){
        $('.sortOptionBtn').removeClass('active');
        $(this).parent().addClass('active');
    });

    $('.deleteItem').on('submit',function(e) {
        e.preventDefault();
        if (confirm(confirmMsg)) {
            showSpinnerOverlay();
            sendAjaxRequestToWithFormData(baseUrl+"/culture/deleteItem",this);
            $(this).parents('.resultBox').remove();
        }
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